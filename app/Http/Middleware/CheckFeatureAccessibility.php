<?php

namespace App\Http\Middleware;

use App\Exceptions\FeatureNotAccessibleException;
use Closure;
use Illuminate\Http\Request;
use YlsIdeas\FeatureFlags\Facades\Features;
use App\Enums\Feature as FeatureEnum;

class CheckFeatureAccessibility
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $feature, $state, $responseCode=null, $errorMessage="")
    {
        if (Features::accessible($feature)!=$state){
            throw new FeatureNotAccessibleException(FeatureEnum::from($feature), $errorMessage, $responseCode);
        }
        return $next($request);
    }
}
