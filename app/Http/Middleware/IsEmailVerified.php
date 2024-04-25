<?php

namespace App\Http\Middleware;

use App\Exceptions\EmailNotVerifiedException;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsEmailVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user= User::where("email",$request->email)->first();
        if ($user && !$user->is_email_verified) {
            auth()->logout();
            throw new EmailNotVerifiedException(trans("messages.verification.email_not_verified"));
        }

        return $next($request);
    }
}
