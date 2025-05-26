<?php
/**
 * Configuration file for whitelisted IP addresses.
 *
 * This file returns an associative array containing the allowed IP addresses
 * that can access the application. The IPs are read from an environment variable
 * and can be modified as needed.
 *
 * Example usage:
 * ```php
 * $config = include('/path/to/config/whitelist-ip.php');
 * $allowedIps = $config['allowed_ips'];
 * ```
 *
 * Note: Ensure this file is not publicly accessible to prevent exposure of sensitive data.
 */

return [
    'allowed_ips' => collect(explode(',', env('ALLOWED_IPS', '')))
        ->map(fn($ip) => trim($ip))
        ->filter(fn($ip) => !empty($ip) && is_string($ip))
        ->values()
        ->toArray(),
];