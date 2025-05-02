<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RestrictIpMiddleware
{
    protected $allowedIps;

    public function __construct()
    {
        // Ambil IP dari ENV, pisahkan dengan koma
        $this->allowedIps = explode(',', env('ALLOWED_IPS', ''));
    }

    public function handle(Request $request, Closure $next): Response
    {
        foreach ($this->allowedIps as $range) {
            if ($this->ipInRange($request->ip(), trim($range))) {
                return $next($request);
            }
        }

        abort(403, 'Access denied');
    }

    protected function ipInRange($ip, $cidr)
    {
        list($subnet, $mask) = explode('/', $cidr);

        $ipDecimal = ip2long($ip);
        $subnetDecimal = ip2long($subnet);
        $maskDecimal = -1 << (32 - (int)$mask);

        return ($ipDecimal & $maskDecimal) == ($subnetDecimal & $maskDecimal);
    }
}
