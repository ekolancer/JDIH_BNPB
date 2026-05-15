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
    }

    public function handle(Request $request, Closure $next): Response
    {
        $clientIp = $request->ip();

        // Izinkan localhost (IPv4 dan IPv6)
        if (in_array($clientIp, ['127.0.0.1', '::1'])) {
            Log::channel('ipaccess')->info("ACCESS GRANTED - Localhost", ['ip' => $clientIp]);
            return $next($request);
        }

        foreach ($this->allowedIps as $range) {
            if ($this->ipInRange($clientIp, $range)) {
                Log::channel('ipaccess')->info("ACCESS GRANTED", [
                    'ip' => $clientIp,
                    'matched_range' => $range,
                ]);
                return $next($request);
            }
        }

        Log::channel('ipaccess')->warning("ACCESS DENIED", ['ip' => $clientIp]);

        abort(403, 'Access denied');
    }

    /**
     * Cek apakah IP berada dalam range (mendukung IPv4 dan IPv6 CIDR).
     */
    protected function ipInRange(string $ip, string $range): bool
    {
        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            return false;
        }

        // CIDR format: 10.220.52.0/22 atau 2001:db8::/32
        if (str_contains($range, '/')) {
            [$subnet, $bits] = explode('/', $range, 2);
            $bits = (int) $bits;

            if (!filter_var($subnet, FILTER_VALIDATE_IP)) {
                return false;
            }

            // Tentukan apakah IPv4 atau IPv6
            $isIpv6Subnet = filter_var($subnet, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
            $isIpv6Ip = filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);

            // Jika tipe IP tidak cocok (IPv4 vs IPv6), skip
            if (($isIpv6Subnet && !$isIpv6Ip) || (!$isIpv6Subnet && $isIpv6Ip)) {
                return false;
            }

            if ($isIpv6Subnet) {
                return $this->ipv6InCidr($ip, $subnet, $bits);
            }

            return $this->ipv4InCidr($ip, $subnet, $bits);
        }

        // Single IP comparison (exact match)
        return $ip === $range;
    }

    /**
     * IPv4 CIDR check.
     */
    protected function ipv4InCidr(string $ip, string $subnet, int $bits): bool
    {
        $ipDecimal = ip2long($ip);
        $subnetDecimal = ip2long($subnet);

        if ($ipDecimal === false || $subnetDecimal === false) {
            return false;
        }

        $maskDecimal = -1 << (32 - $bits);
        $subnetStart = $subnetDecimal & $maskDecimal;
        $subnetEnd = $subnetStart | ~$maskDecimal;

        return $ipDecimal >= $subnetStart && $ipDecimal <= $subnetEnd;
    }

    /**
     * IPv6 CIDR check menggunakan binary string comparison.
     */
    protected function ipv6InCidr(string $ip, string $subnet, int $bits): bool
    {
        $ipBin = $this->ipv6ToBinary($ip);
        $subnetBin = $this->ipv6ToBinary($subnet);

        if ($ipBin === null || $subnetBin === null) {
            return false;
        }

        // Bandingkan N bit pertama
        $ipPrefix = substr($ipBin, 0, $bits);
        $subnetPrefix = substr($subnetBin, 0, $bits);

        return $ipPrefix === $subnetPrefix;
    }

    /**
     * Konversi IPv6 address ke binary string (128 bit).
     */
    protected function ipv6ToBinary(string $ip): ?string
    {
        $packed = @inet_pton($ip);

        if ($packed === false) {
            return null;
        }

        $binary = '';
        foreach (unpack('C*', $packed) as $byte) {
            $binary .= str_pad(decbin($byte), 8, '0', STR_PAD_LEFT);
        }

        return $binary;
    }
}
