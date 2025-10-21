<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Jaybizzle\CrawlerDetect\CrawlerDetect;
use Illuminate\Support\Facades\Log;

class VisitorCounter
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // 🚫 Abaikan route yang tidak relevan
        if ($request->is('webmin/*') || $request->is('api/*') || $request->is('telescope*')) {
            return $response;
        }

        $userAgent = $request->userAgent() ?? 'unknown';
        $ip = $request->ip();
        $path = $request->path();
        $referrer = $request->headers->get('referer', 'Direct');

        // 🚫 Abaikan bot/crawler
        $crawler = new CrawlerDetect;
        if ($crawler->isCrawler($userAgent)) {
            return $response;
        }

        // 🧭 Ambil lokasi GEOIP dengan fallback tanpa cache
        $location = null;
        try {
            $location = geoip()->getLocation($ip, false); // tanpa cache
        } catch (\Throwable $e) {
            Log::warning('GeoIP lookup failed: ' . $e->getMessage());
        }

        $isLocal = $this->isLocalIp($ip);

        if ($isLocal) {
            $country = 'indonesia';
            $city = 'Local Network';
        } else {
            $country = strtolower($location->country ?? '');
            $city = $location->city ?? null;
        }

        // 🚫 Abaikan negara lain, tapi izinkan jika lokal/dev
        if (!$isLocal && $country && $country !== 'indonesia') {
            return $response;
        }

        // 🚨 Deteksi aktivitas mencurigakan
        $suspicious = $this->isSuspicious($request, $userAgent);

        // ⚙️ Kurangi overhead query: cek visitor hanya 1x/hari
        $exists = Visitor::whereDate('visited_at', now()->toDateString())
            ->where('ip_address', $ip)
            ->where('user_agent', substr($userAgent, 0, 255))
            ->exists();

        if (!$exists) {
            try {
                Visitor::create([
                    'ip_address' => $ip,
                    'user_agent' => substr($userAgent, 0, 255),
                    'path'       => $path,
                    'referrer'   => $referrer,
                    'country'    => ucfirst($country ?: 'Unknown'),
                    'city'       => $city,
                    'suspicious' => $suspicious,
                    'visited_at' => now(),
                ]);
            } catch (\Throwable $e) {
                Log::error('Failed to create visitor record: ' . $e->getMessage());
            }
        }

        // 📋 (Opsional) Log ringan untuk debugging dev
        if (app()->environment('local')) {
            Log::info('Visitor logged', [
                'ip' => $ip,
                'path' => $path,
                'country' => $country,
                'city' => $city,
                'local' => $isLocal,
                'suspicious' => $suspicious,
            ]);
        }

        return $response;
    }

    /**
     * Cek apakah IP termasuk lokal/dev.
     */
    private function isLocalIp(string $ip): bool
    {
        return str_starts_with($ip, '127.')
            || str_starts_with($ip, '192.168.')
            || str_starts_with($ip, '10.')
            || $ip === '::1';
    }

    /**
     * Deteksi aktivitas mencurigakan berdasarkan pola User-Agent.
     */
    private function isSuspicious(Request $request, string $userAgent): bool
    {
        $ua = strtolower($userAgent);
        $patterns = [
            'sqlmap',
            'curl/',
            'wget',
            'python-requests',
            'nmap',
            'masscan',
            'zgrab',
            'libwww-perl',
            'httpclient',
        ];

        foreach ($patterns as $pattern) {
            if (str_contains($ua, $pattern)) {
                return true;
            }
        }

        // Tambahan: terlalu banyak request dalam 5 detik dari IP sama (opsional)
        // Bisa implementasikan rate limiting manual jika perlu

        return false;
    }
}
