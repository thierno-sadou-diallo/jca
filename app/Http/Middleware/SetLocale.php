<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $routeLocale = $request->route('locale');
        $locale = in_array($routeLocale, ['fr', 'en'], true)
            ? $routeLocale
            : session('locale', config('app.locale', 'fr'));

        if (in_array($locale, ['fr', 'en'], true)) {
            App::setLocale($locale);
        }

        return $next($request);
    }
}
