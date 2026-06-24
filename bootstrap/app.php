<?php

use App\Http\Middleware\VerifyTurnstile;
use App\Http\Middleware\VisitorCounter;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        apiPrefix: '',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            VisitorCounter::class,
        ]);

        $middleware->alias([
            'turnstile' => VerifyTurnstile::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {

        // 🔴 HANDLE SAAT EXCEPTION DIRENDER (INI KUNCI!)
        $exceptions->render(function (Throwable $e, $request) {

            $status = null;

            if ($e instanceof NotFoundHttpException) {
                $status = 404;
            }

            if ($e instanceof HttpException) {
                $status = $e->getStatusCode();
            }

            if (in_array($status, [403, 404])) {

                Log::channel('security')->warning("HTTP {$status}", [
                    'status' => $status,
                    'url' => $request->fullUrl(),
                    'path' => $request->path(),
                    'method' => $request->method(),
                    'ip' => $request->ip(),
                    'ua' => $request->userAgent(),
                ]);
            }

            return null; // penting: lanjutkan default Laravel
        });
    })->create();
