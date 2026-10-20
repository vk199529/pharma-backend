<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;

/**
 * Language from the URL: /en/... and /es/...
 * Routes without a prefix (search, RFQ, forms, pdf) use the language saved in the session.
 * The Backpack admin panel is NOT touched.
 *
 * Save as: app/Http/Middleware/SetLocale.php
 */
class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->is(trim(config('backpack.base.route_prefix', 'admin'), '/') . '*')) {
            return $next($request);
        }

        $available = config('app.available_locales', ['en', 'es']);

        $locale = $request->route('locale');
        if (! in_array($locale, $available, true)) {
            $locale = session('site_locale', config('app.locale'));
        }
        if (! in_array($locale, $available, true)) {
            $locale = config('app.locale');
        }

        session(['site_locale' => $locale]);
        app()->setLocale($locale);
        URL::defaults(['locale' => $locale]);

       
        $request->route()?->forgetParameter('locale');

      
        View::share('localizedUrl', function (string $code) use ($request) {
            $route = $request->route();
            $name  = $route?->getName();

            if (! $name || ! Route::has($name) || ! str_contains((string) $route->uri(), '{locale}')) {
                return url('/' . $code);             
            }

            return route($name, array_merge($route->parameters(), ['locale' => $code]) + $request->query());
        });

        return $next($request);
    }
}