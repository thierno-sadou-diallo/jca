<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use App\Http\Middleware\SetLocale;
use App\Http\Middleware\TranslateRenderedPage;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            SetLocale::class,
            TranslateRenderedPage::class,
        ]);

        $middleware->redirectGuestsTo(fn (Request $request) => $request->is('admin*')
            ? route('admin.login')
            : route('portal.login'));
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
