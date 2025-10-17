<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Jaybizzle\CrawlerDetect\CrawlerDetect;

class VisitorCounter
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($request->is('webmin/*') || $request->is('api/*')) {
            return $response;
        }

        $userAgent = $request->userAgent();

        $crawler = new CrawlerDetect;

        if (!$userAgent || $crawler->isCrawler($userAgent)) {
            return $response; // Abaikan bot/crawler
        }

        $ip = $request->ip();
        $userAgent = $request->userAgent();

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
