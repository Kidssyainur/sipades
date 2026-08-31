<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            \Illuminate\Support\Facades\Route::middleware('web')
                ->group(__DIR__.'/../routes/portal.php');
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Alias middleware role/permission dari spatie/laravel-permission (PRD §13).
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
        ]);

        // Trust all proxies for Cloudflare Tunnel & reverse proxies (HTTPS header forwarding)
        $middleware->trustProxies(at: '*');

        // Ganti default route('login') (yang tidak terdefinisi) dengan halaman login portal warga.
        $middleware->redirectGuestsTo(fn () => route('portal.login'));
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();
