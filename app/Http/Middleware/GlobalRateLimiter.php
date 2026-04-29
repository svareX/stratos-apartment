<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class GlobalRateLimiter
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $route = $request->route()?->getName() ?? $request->path();
        $key = 'global-rate:'.$request->ip().':'.Str::slug($route);

        $max = config('rate_limit.global.max_requests', 300);
        $decay = config('rate_limit.global.decay_minutes', 1);

        if (RateLimiter::tooManyAttempts($key, $max)) {
            $available = RateLimiter::availableIn($key);

            return response('Too Many Requests', 429)
                ->header('Retry-After', $available);
        }

        RateLimiter::hit($key, $decay * 60);

        return $next($request);
    }
}
