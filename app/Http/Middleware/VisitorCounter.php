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

        // Abaikan akses dari admin panel & API
        if ($request->is('webmin/*') || $request->is('api/*')) {
            return $response;
        }

        $userAgent = $request->userAgent();
        $crawler = new CrawlerDetect;

        // Abaikan bot/crawler
        if (!$userAgent || $crawler->isCrawler($userAgent)) {
            return $response;
        }

        $ip = $request->ip();

        /**
         * ✅ Gunakan geoip tanpa cache, agar tidak memicu error cache tagging.
         * Jika gagal (misal service offline), maka $location = null.
         */
        try {
            $location = geoip()->getLocation($ip, false); // false = no cache
        } catch (\Throwable $e) {
            Log::warning('GeoIP lookup failed: ' . $e->getMessage());
            $location = null;
        }

        // Hanya izinkan visitor dari Indonesia
        $country = strtolower($location->country ?? '');
        if ($country !== 'indonesia') {
            return $response;
        }

        // Cegah duplikat visitor per hari berdasarkan IP
        $exists = Visitor::whereDate('visited_at', now()->toDateString())
            ->where('ip_address', $ip)
            ->exists();

        if (!$exists) {
            Visitor::create([
                'ip_address' => $ip,
                'user_agent' => substr($userAgent, 0, 255),
                'visited_at' => now(),
            ]);
        }

        return $response;
    }
}
