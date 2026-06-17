<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class VerifyTurnstile
{
    public function handle(Request $request, Closure $next): Response
    {
        // Skip jika Turnstile belum dikonfigurasi (development)
        $secretKey = config('turnstile.secret_key');
        if (empty($secretKey)) {
            return $next($request);
        }

        $token = $request->input('cf-turnstile-response');

        if (empty($token)) {
            return $this->failResponse($request, 'Verifikasi Turnstile diperlukan.');
        }

        // Verifikasi token ke Cloudflare
        try {
            $response = Http::asForm()->post(config('turnstile.verify_url'), [
                'secret' => $secretKey,
                'response' => $token,
                'remoteip' => $request->ip(),
            ]);

            $result = $response->json();

            if (! ($result['success'] ?? false)) {
                Log::channel('single')->warning('Turnstile verification failed', [
                    'ip' => $request->ip(),
                    'errors' => $result['error-codes'] ?? [],
                ]);

                return $this->failResponse($request, 'Verifikasi gagal. Silakan coba lagi.');
            }
        } catch (\Throwable $e) {
            Log::channel('single')->error('Turnstile API error', [
                'error' => $e->getMessage(),
            ]);

            if (config('turnstile.fail_open')) {
                return $next($request);
            }

            return $this->failResponse($request, 'Verifikasi keamanan sedang tidak tersedia. Silakan coba lagi nanti.');
        }

        return $next($request);
    }

    private function failResponse(Request $request, string $message): Response
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => $message], 422);
        }

        return back()->withErrors(['turnstile' => $message]);
    }
}
