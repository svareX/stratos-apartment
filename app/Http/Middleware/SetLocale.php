<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class SetLocale
{
    /**
     * Handle an incoming request by setting application locale from the route parameter.
     */
    public function handle(Request $request, Closure $next)
    {
        $locales = ['cs', 'en', 'de'];

        $locale = $request->route('locale') ?? config('app.locale', 'en');

        if (! in_array($locale, $locales)) {
            abort(404);
        }

        app()->setLocale($locale);

        URL::defaults(['locale' => $locale]);

        return $next($request);
    }
}
