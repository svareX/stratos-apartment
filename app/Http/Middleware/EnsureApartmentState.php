<?php

namespace App\Http\Middleware;

use App\Models\Apartment;
use Closure;
use Illuminate\Http\Request;

class EnsureApartmentState
{
    /**
     * Handle an incoming request.
     * Usage in routes: ->middleware(\App\Http\Middleware\EnsureApartmentState::class.':active')
     * or use ':inactive' to require inactive apartments.
     */
    public function handle(Request $request, Closure $next, string $required = 'active')
    {
        $slug = $request->route('apartment');

        $apartment = Apartment::where('slug', $slug)->first();

        if (! $apartment) {
            abort(404);
        }

        $isActive = (bool) $apartment->active;

        if ($required === 'active' && ! $isActive) {
            return redirect()->route('apartments.unavailable', ['locale' => app()->getLocale(), 'apartment' => $apartment->slug]);
        }

        if ($required === 'inactive' && $isActive) {
            return redirect()->route('apartments.show', ['locale' => app()->getLocale(), 'apartment' => $apartment->slug]);
        }

        return $next($request);
    }
}
