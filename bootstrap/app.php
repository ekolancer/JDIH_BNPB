<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            \App\Http\Middleware\VisitorCounter::class,
        ]);

        $middleware->alias([
            'turnstile' => \App\Http\Middleware\VerifyTurnstile::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {

        // 🔴 HANDLE SAAT EXCEPTION DIRENDER (INI KUNCI!)
        $exceptions->render(function (\Throwable $e, $request) {

            $status = null;

            if ($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
                $status = 404;
            }

            if ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpException) {
                $status = $e->getStatusCode();
            }

            if (in_array($status, [403, 404])) {

                Log::channel('security')->warning("HTTP {$status}", [
                    'status' => $status,
                    'url'    => $request->fullUrl(),
                    'path'   => $request->path(),
                    'method' => $request->method(),
                    'ip'     => $request->ip(),
                    'ua'     => $request->userAgent(),
                ]);
            }

            return null; // penting: lanjutkan default Laravel
        });
    })->create();
