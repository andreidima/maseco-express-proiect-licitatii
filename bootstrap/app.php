<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

use App\Http\Middleware\CheckUserActiv;
use App\Http\Middleware\CheckUserRole;
use App\Http\Middleware\EnsureParticipantHasCarrier;
use App\Http\Middleware\SetLocale;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            SetLocale::class,
        ]);

        $middleware->alias([
            'checkUserActiv' => CheckUserActiv::class,
            'checkUserRole' => CheckUserRole::class,
            'ensureParticipantHasCarrier' => EnsureParticipantHasCarrier::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
