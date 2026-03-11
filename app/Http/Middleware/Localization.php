<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $locale = null;

        if (session()->has('locale')) {
            $locale = session()->get('locale');
        } elseif ($request->cookie('preferred_language')) {
            $locale = $request->cookie('preferred_language');
            session()->put('locale', $locale);
        }

        if ($locale && in_array($locale, ['cs', 'en', 'de'])) {
            App::setlocale($locale);
        }

        return $next($request);
    }
}
