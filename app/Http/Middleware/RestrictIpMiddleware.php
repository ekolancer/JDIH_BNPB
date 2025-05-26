<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class RestrictIpMiddleware
{
    protected $allowedIps;

    public function __construct()
    {
        $this->allowedIps = config('whitelist-ip.allowed_ips', []);
        // Debug sementara
        // dd($this->allowedIps);
    }

    public function handle(Request $request, Closure $next): Response
    {
        // Izinkan akses jika IP adalah localhost
        $clientIp = $request->ip();

        // Izinkan localhost
        if (in_array($clientIp, ['127.0.0.1', '::1'])) {
            Log::channel('ipaccess')->info("ACCESS GRANTED - Localhost", ['ip' => $clientIp]);
            return $next($request);
        }

        foreach ($this->allowedIps as $range) {
            if ($this->ipInRange($clientIp, $range)) {
                Log::channel('ipaccess')->info("ACCESS GRANTED", [
                    'ip' => $clientIp,
                    'matched_range' => $range
                ]);
                return $next($request);
            }
        }

        // Jika tidak cocok
        Log::channel('ipaccess')->warning("ACCESS DENIED", ['ip' => $clientIp]);

        abort(403, 'Access denied');
    }

    protected function ipInRange($ip, $range)
    {
        // Pastikan format IP valid
        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            return false;
        }

        // CIDR format, contoh: 10.220.52.0/22
        if (strpos($range, '/') !== false) {
            [$subnet, $mask] = explode('/', $range);

            // Validasi IP subnet juga
            if (!filter_var($subnet, FILTER_VALIDATE_IP)) {
                return false;
            }

            $ipDecimal = ip2long($ip);
            $subnetDecimal = ip2long($subnet);

            if ($ipDecimal === false || $subnetDecimal === false) {
                return false;
            }

            $maskDecimal = -1 << (32 - (int)$mask);
            $subnetStart = $subnetDecimal & $maskDecimal;
            $subnetEnd = $subnetStart | ~$maskDecimal;

            return $ipDecimal >= $subnetStart && $ipDecimal <= $subnetEnd;
        }

        // Single IP comparison
        return $ip === $range;
    }
}
// This middleware checks if the request's IP address is in the allowed list.
// If the IP is localhost, it allows access. Otherwise, it checks against the allowed IPs.
// If the IP is not allowed, it returns a 403 Forbidden response.