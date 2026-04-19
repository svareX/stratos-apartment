<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RequireWebsitePassword
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $password = env('WEBSITE_PASSWORD', '');

        if ($password === '') {
            return $next($request);
        }

        if ($request->session()->get('website_authenticated', false)) {
            return $next($request);
        }

        $allowedPatterns = [
            'password',
            'password/*',
            'css/*',
            'js/*',
            'images/*',
            'fonts/*',
            'favicon.ico',
            'hot',
        ];

        foreach ($allowedPatterns as $pattern) {
            if ($request->is($pattern)) {
                return $next($request);
            }
        }

        return redirect()->route('password.form');
    }
}
