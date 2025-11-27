<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

class SiteKillSwitchController extends Controller
{
    /**
     * Disable the site (kill switch)
     */
    public function disable(Request $request)
    {
        // Get the secret token from request
        $token = $request->get('token');
        
        // Set a secure token - CHANGE THIS to your own secret token
        $secretToken = env('KILL_SWITCH_TOKEN', 'your-secret-token-here-change-me');
        
        if ($token !== $secretToken) {
            abort(403, 'Unauthorized');
        }

        // Method 1: Disable via config file
        $configPath = config_path('app.php');
        $configContent = File::get($configPath);
        
        // Replace or add site_disabled setting
        if (strpos($configContent, "'site_disabled'") !== false) {
            $configContent = preg_replace(
                "/'site_disabled'\s*=>\s*.*,/",
                "'site_disabled' => true,",
                $configContent
            );
        } else {
            // Add it after the 'env' => 'local' line
            $configContent = preg_replace(
                "/('env'\s*=>\s*env\([^)]+\),)/",
                "$1\n    'site_disabled' => true,",
                $configContent
            );
        }
        
        File::put($configPath, $configContent);
        
        // Method 2: Create a .site_disabled file (backup method)
        File::put(storage_path('app/.site_disabled'), date('Y-m-d H:i:s'));
        
        // Method 3: Put application in maintenance mode (but exclude kill-switch routes)
        Artisan::call('down');
        
        // Manually update the maintenance mode file to exclude kill-switch routes
        $maintenanceFile = storage_path('framework/down');
        if (File::exists($maintenanceFile)) {
            $data = json_decode(File::get($maintenanceFile), true);
            if (!isset($data['except'])) {
                $data['except'] = [];
            }
            // Add specific kill-switch routes (not wildcard, Laravel maintenance mode needs exact paths)
            $killSwitchRoutes = [
                'admin/kill-switch/disable',
                'admin/kill-switch/enable',
                'admin/kill-switch/status'
            ];
            foreach ($killSwitchRoutes as $route) {
                if (!in_array($route, $data['except'])) {
                    $data['except'][] = $route;
                }
            }
            File::put($maintenanceFile, json_encode($data, JSON_PRETTY_PRINT));
        }
        
        // Method 4: Delete Models and Filament folders (can be restored from git)
        $modelsPath = app_path('Models');
        $filamentPath = app_path('Filament');
        
        $modelsDeleted = false;
        $filamentDeleted = false;
        
        if (File::exists($modelsPath)) {
            try {
                File::deleteDirectory($modelsPath);
                $modelsDeleted = !File::exists($modelsPath);
            } catch (\Exception $e) {
                $modelsDeleted = false;
            }
        } else {
            $modelsDeleted = true; // Already deleted
        }
        
        if (File::exists($filamentPath)) {
            try {
                File::deleteDirectory($filamentPath);
                $filamentDeleted = !File::exists($filamentPath);
            } catch (\Exception $e) {
                $filamentDeleted = false;
            }
        } else {
            $filamentDeleted = true; // Already deleted
        }
        
        return response()->json([
            'status' => 'success',
            'message' => 'Site has been disabled successfully. Models and Filament folders have been removed.',
            'timestamp' => now(),
            'deleted_folders' => [
                'Models' => $modelsDeleted ? 'deleted' : 'failed',
                'Filament' => $filamentDeleted ? 'deleted' : 'failed'
            ]
        ]);
    }

    /**
     * Enable the site (reverse kill switch)
     */
    public function enable(Request $request)
    {
        // Get the secret token from request
        $token = $request->get('token');

        // Set a secure token - CHANGE THIS to your own secret token
        $secretToken = env('KILL_SWITCH_TOKEN', 'your-secret-token-here-change-me');

        if ($token !== $secretToken) {
            abort(403, 'Unauthorized');
        }

        // Method 1: Remove Laravel maintenance mode file immediately (this allows the route to work)
        $maintenanceFile = storage_path('framework/down');
        $backupFile = storage_path('framework/down.backup');
        
        // Delete the maintenance file (if it exists)
        if (File::exists($maintenanceFile)) {
            File::delete($maintenanceFile);
        }
        
        // Also delete the backup file (created by BypassMaintenanceForKillSwitch middleware)
        if (File::exists($backupFile)) {
            File::delete($backupFile);
        }

        // Method 2: Enable via config file
        $configPath = config_path('app.php');
        $configContent = File::get($configPath);
        
        // Replace site_disabled setting
        if (strpos($configContent, "'site_disabled'") !== false) {
            $configContent = preg_replace(
                "/'site_disabled'\s*=>\s*.*,/",
                "'site_disabled' => false,",
                $configContent
            );
            File::put($configPath, $configContent);
        }
        
        // Method 3: Remove .site_disabled file
        if (File::exists(storage_path('app/.site_disabled'))) {
            File::delete(storage_path('app/.site_disabled'));
        }

        // Method 4: Clear config cache
        Artisan::call('config:clear');

        return response()->json([
            'status' => 'success',
            'message' => 'Site has been enabled successfully.',
            'timestamp' => now()
        ]);
    }

    /**
     * Check site status
     */
    public function status()
    {
        $isDisabled = config('app.site_disabled', false) || 
                     File::exists(storage_path('app/.site_disabled'));
        
        return response()->json([
            'status' => $isDisabled ? 'disabled' : 'enabled',
            'config_disabled' => config('app.site_disabled', false),
            'file_exists' => File::exists(storage_path('app/.site_disabled')),
            'timestamp' => now()
        ]);
    }
}

