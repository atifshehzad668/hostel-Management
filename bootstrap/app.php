<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            // 'admin.auth' => App\Http\Middleware\AdminAuthenticate::class,
            // 'auth' => App\Http\Middleware\Authenticate::class,
            // 'guest' => App\Http\Middleware\RedirectIfAuthenticated::class,
            // 'admin.guest' => App\Http\Middleware\AdminRedirectIfAuthenticated::class,
            // 'Image' => Intervention\Image\Facades\Image::class,


        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();