<?php

namespace App\Http\Middleware;

use App\Jobs\ResolveVisitorGeo;
use App\Models\Visitor;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Jaybizzle\CrawlerDetect\CrawlerDetect;
use Symfony\Component\HttpFoundation\Response;

class VisitorCounter
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // 🚫 Abaikan route tertentu (admin & API)
        if ($request->is('webmin/*') || $request->is('api/*')) {
            return $response;
        }

        $ip = $request->ip();
        $userAgent = $request->userAgent() ?? 'Unknown';
        $path = $request->path();
        $referrer = $request->headers->get('referer', 'Direct');

        // 🚫 Abaikan bot/crawler
        $crawler = new CrawlerDetect;
        if ($crawler->isCrawler($userAgent)) {
            return $response;
        }

        // 🚨 Deteksi aktivitas mencurigakan
        $suspicious = $this->isSuspicious($userAgent);

        // 🌐 Deteksi lokasi — IP lokal langsung set Indonesia
        $isLocal = $this->isLocalIp($ip);

        if ($isLocal) {
            $country = 'Indonesia';
            $city = 'Local Network';
        } else {
            // Non-local: simpan dulu dengan country pending, resolve via queue
            $country = null;
            $city = null;
        }

        // ⚙️ Cegah duplikasi log harian
        $exists = Visitor::where('ip_address', $ip)
            ->whereBetween('visited_at', [now()->startOfDay(), now()->endOfDay()])
            ->exists();

        if (! $exists) {
            try {
                $visitor = Visitor::create([
                    'ip_address' => $ip,
                    'user_agent' => substr($userAgent, 0, 255),
                    'path' => $path,
                    'referrer' => $referrer,
                    'country' => $country,
                    'city' => $city,
                    'suspicious' => $suspicious,
                    'visited_at' => now(),
                ]);

                // Dispatch geo-lookup ke queue (non-blocking) untuk IP non-lokal
                if (! $isLocal) {
                    ResolveVisitorGeo::dispatch($visitor->id, $ip);
                }

                Log::channel('visitor')->info('✅ Visitor recorded', [
                    'ip' => $ip,
                    'path' => $path,
                    'country' => $country ?? 'pending-geo',
                    'suspicious' => $suspicious,
                ]);
            } catch (\Throwable $e) {
                Log::channel('visitor')->error('❌ Failed to insert visitor', [
                    'ip' => $ip,
                    'path' => $path,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return $response;
    }

    /**
     * 🔍 Cek apakah IP lokal.
     */
    private function isLocalIp(string $ip): bool
    {
        return str_starts_with($ip, '127.')
            || str_starts_with($ip, '192.168.')
            || str_starts_with($ip, '10.224.')
            || $ip === '::1';
    }

    /**
     * ⚠️ Deteksi user-agent mencurigakan.
     */
    private function isSuspicious(string $userAgent): bool
    {
        $ua = strtolower($userAgent);
        $patterns = [
            'sqlmap', 'curl/', 'wget', 'python-requests', 'nmap',
            'masscan', 'zgrab', 'libwww-perl', 'httpclient', 'scanbot',
        ];

        foreach ($patterns as $pattern) {
            if (str_contains($ua, $pattern)) {
                return true;
            }
        }

        return false;
    }
}
