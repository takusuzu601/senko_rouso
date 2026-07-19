<?php

use App\Http\Middleware\BasicAuthMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Render のロードバランサ(プロキシ)を信頼し、X-Forwarded-Proto=https を
        // 正しく認識させる。これで $request->isSecure() が true になる。
        $middleware->trustProxies(at: '*');
        $middleware->prependToGroup('web', BasicAuthMiddleware::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();
