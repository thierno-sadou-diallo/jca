<?php

namespace App\Providers;

use App\Models\SiteSetting;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->input('email');

            return [
                Limit::perMinute(5)->by(strtolower($email).'|'.$request->ip()),
                Limit::perMinute(20)->by($request->ip()),
            ];
        });

        RateLimiter::for('registration', function (Request $request) {
            return [
                Limit::perMinute(3)->by($request->ip()),
                Limit::perHour(10)->by($request->ip()),
            ];
        });

        RateLimiter::for('lead-requests', function (Request $request) {
            $email = strtolower((string) $request->input('email'));

            return [
                Limit::perMinute(2)->by(($email ?: 'guest').'|'.$request->ip()),
                Limit::perHour(12)->by($request->ip()),
            ];
        });

        View::composer('*', function ($view): void {
            $publicRoute = function (string $routeName, mixed $parameters = [], bool $absolute = true): string {
                $locale = app()->getLocale();

                if ($locale !== 'en' || Str::startsWith($routeName, ['admin.', 'portal.'])) {
                    return route($routeName, $parameters, $absolute);
                }

                $localizedRoute = match ($routeName) {
                    'home' => 'localized.home',
                    'jobs.index' => 'localized.jobs.index',
                    'public.blog' => 'localized.public.blog',
                    'public.news' => 'localized.public.news',
                    'public.articles.show' => 'localized.public.articles.show',
                    'public.faq' => 'localized.public.faq',
                    'public.testimonials.index' => 'localized.public.testimonials.index',
                    'public.partners' => 'localized.public.partners',
                    'public.cooperation-projects' => 'localized.public.cooperation-projects',
                    'public.cooperation-projects.show' => 'localized.public.cooperation-projects.show',
                    'public.humanitarian-programs' => 'localized.public.humanitarian-programs',
                    'public.humanitarian-programs.show' => 'localized.public.humanitarian-programs.show',
                    'legal.show' => 'localized.legal.show',
                    'page.show' => 'localized.page.show',
                    default => null,
                };

                if ($localizedRoute === null) {
                    return route($routeName, $parameters, $absolute);
                }

                $localizedParameters = is_array($parameters)
                    ? array_merge(['locale' => $locale], $parameters)
                    : [$locale, $parameters];

                return route($localizedRoute, $localizedParameters, $absolute);
            };

            $view->with('siteSettings', SiteSetting::publicValues());
            $view->with('publicRoute', $publicRoute);
        });
    }
}
