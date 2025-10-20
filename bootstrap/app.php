<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {

            Route::prefix("admin/v1")
                ->middleware("api")
                ->name("admin.")
                ->namespace("App\Http\Controllers\Admin\V1")
                ->group(function () {
                    require base_path('routes/admin/v1.php');
                });

            Route::prefix('api/v1')
                ->middleware("api")
                ->name("client.")
                ->namespace("App\Http\Controllers\Client\V1")
                ->group(function () {
                    require base_path('routes/client/v1.php');
                });
            Route::prefix('api/v2')
                ->middleware("api")
                ->name("admin.v2.")
                ->namespace("App\Http\Controllers\Admin\V1")
                ->group(function () {
                    require base_path('routes/client/v2.php');
                });

        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(\App\Http\Middleware\SetAuthorizationMiddleware::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
