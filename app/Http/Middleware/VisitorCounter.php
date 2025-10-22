<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Jaybizzle\CrawlerDetect\CrawlerDetect;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

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

        // 🌐 Deteksi lokasi via API ipapi.co
        $isLocal = $this->isLocalIp($ip);
        $country = 'Unknown';
        $city = '-';

        if ($isLocal) {
            $country = 'Indonesia';
            $city = 'Local Network';
        } else {
            try {
                $apiResponse = Http::timeout(3)->get("https://ipapi.co/{$ip}/json/");
                $data = $apiResponse->json();

                // Jika rate-limited atau gagal, fallback ke ipwho.is
                if (isset($data['error']) || !isset($data['country_name'])) {
                    Log::info("ipapi.co failed or rate limited for IP {$ip}, using ipwho.is fallback.");
                    $apiResponse = Http::timeout(3)->get("https://ipwho.is/{$ip}");
                    $data = $apiResponse->json();
                    Log::info("ipwho.is result for {$ip}: " . json_encode([
                        'country' => $data['country_name'] ?? null,
                        'city' => $data['city'] ?? null,
                    ]));
                }


                $country = $data['country_name'] ?? 'Unknown';
                $city = $data['city'] ?? '-';
            } catch (\Throwable $e) {
                Log::warning("Geo lookup failed for IP {$ip}: " . $e->getMessage());
            }
        }

        // 🚫 Abaikan pengunjung luar negeri (kecuali IP lokal)
        if (!$isLocal && strtolower($country) !== 'indonesia') {
            return $response;
        }

        // 🚨 Deteksi aktivitas mencurigakan
        $suspicious = $this->isSuspicious($userAgent);

        // ⚙️ Kurangi query duplikat: 1x/hari per kombinasi IP + user agent
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
                    'country'    => $country,
                    'city'       => $city,
                    'suspicious' => $suspicious,
                    'visited_at' => now(),
                ]);
            } catch (\Throwable $e) {
                Log::error('Failed to insert visitor: ' . $e->getMessage(), [
                    'ip' => $ip,
                    'path' => $path,
                ]);
            }
        }

        // 🧾 Logging ringan (aktif di local)
        if (app()->environment('local')) {
            Log::info('Visitor recorded', [
                'ip' => $ip,
                'path' => $path,
                'country' => $country,
                'city' => $city,
                'suspicious' => $suspicious,
                'local' => $isLocal,
            ]);
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
            || str_starts_with($ip, '10.')
            || $ip === '::1';
    }

    /**
     * ⚠️ Deteksi user-agent mencurigakan.
     */
    private function isSuspicious(string $userAgent): bool
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
            'scanbot',
        ];

        foreach ($patterns as $pattern) {
            if (str_contains($ua, $pattern)) {
                return true;
            }
        }

        return false;
    }
}
