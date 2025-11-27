<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SiteKillSwitch
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
        // First, check if this is a kill switch route - allow it through immediately
        $uri = $request->getRequestUri();
        $path = trim(parse_url($uri, PHP_URL_PATH), '/');
        
        // Check multiple ways to identify kill switch routes
        if (strpos($path, 'admin/kill-switch') === 0 || 
            strpos($uri, '/admin/kill-switch') !== false ||
            strpos($path, 'kill-switch') !== false) {
            return $next($request);
        }
        // Check if site is disabled via config or file
        $isDisabled = config('app.site_disabled', false) ||
                     \Illuminate\Support\Facades\File::exists(storage_path('app/.site_disabled'));

        if ($isDisabled) {
            // Show maintenance page for all other routes
            return response()->view('site.disabled', [], 503);
        }

        return $next($request);
    }
}

