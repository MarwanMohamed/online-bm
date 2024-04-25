<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;

class LogoutIfInactive
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @throws \Illuminate\Auth\AuthenticationException
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && ! auth()->user()->is_active) {
            me()->tokens()->delete();

            auth('web')->logout();

            throw new AuthenticationException(__('auth.inactive'));
        }

        return $next($request);
    }
}
