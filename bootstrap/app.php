<?php

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectGuestsTo(fn () => route('portal.login'));
        $middleware->redirectUsersTo(fn () => route('portal.dashboard'));
        $middleware->validateCsrfTokens(except: [
            'webhooks/paystack',
            'webhooks/flutterwave',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );

        $exceptions->render(function (Throwable $exception, Request $request) {
            $forbidden = $exception instanceof AuthorizationException
                || ($exception instanceof HttpException && $exception->getStatusCode() === 403);

            if (! $forbidden || $request->expectsJson() || $request->is('api/*') || ! $request->is('portal', 'portal/*')) {
                return null;
            }

            return response()->view('portal.forbidden', status: 403);
        });
    })->create();
