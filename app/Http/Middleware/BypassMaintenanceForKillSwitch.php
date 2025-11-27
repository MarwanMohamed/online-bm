<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BypassMaintenanceForKillSwitch
{
    /**
     * Handle an incoming request.
     * This middleware runs BEFORE CheckForMaintenanceMode to allow kill-switch routes
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if this is a kill-switch route
        $path = trim($request->path(), '/');
        
        if (strpos($path, 'admin/kill-switch') === 0) {
            // Temporarily disable maintenance mode for this request
            $maintenanceFile = storage_path('framework/down');
            $backupFile = storage_path('framework/down.backup');
            $hadMaintenanceFile = file_exists($maintenanceFile);
            
            // If maintenance file exists, temporarily rename it
            if ($hadMaintenanceFile) {
                rename($maintenanceFile, $backupFile);
            }
            
            // Process the request
            $response = $next($request);
            
            // Check if this was an enable request (by checking the response content)
            $isEnableRequest = strpos($path, 'admin/kill-switch/enable') === 0;
            
            // If this was an enable request, don't restore the maintenance file
            // The enable route will handle deleting both the maintenance file and backup
            if ($isEnableRequest) {
                // Enable route handles cleanup, so don't restore
                if (file_exists($backupFile) && !file_exists($maintenanceFile)) {
                    // Backup still exists but maintenance file was deleted - clean up backup
                    unlink($backupFile);
                }
            } else {
                // For other kill-switch routes (disable, status), restore maintenance file if needed
                if ($hadMaintenanceFile && file_exists($backupFile) && !file_exists($maintenanceFile)) {
                    rename($backupFile, $maintenanceFile);
                }
            }
            
            return $response;
        }
        
        return $next($request);
    }
}

