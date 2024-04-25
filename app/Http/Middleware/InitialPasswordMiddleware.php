<?php

namespace App\Http\Middleware;

use App\Exceptions\InitialPasswordChangedException;
use Closure;
use Illuminate\Http\Request;

class InitialPasswordMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @throws \App\Exceptions\InitialPasswordChangedException
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (me() && me()->is_initial_password_changed) {
            throw new InitialPasswordChangedException();
        }

        return $next($request);
    }
}
