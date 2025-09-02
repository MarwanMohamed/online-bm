<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Filament\Notifications\Notification;

class HandleFilamentModelNotFound
{
    public function handle(Request $request, Closure $next)
    {
        try {
            return $next($request);
        } catch (ModelNotFoundException $e) {
            if ($request->is('admin/*')) {
                $model = class_basename($e->getModel());
                $ids = implode(', ', $e->getIds());
                
                Notification::make()
                    ->title('Record Not Found')
                    ->body("The requested {$model} record (ID: {$ids}) was not found or may have been deleted.")
                    ->danger()
                    ->persistent()
                    ->send();
                
                // Redirect to the appropriate index page based on the current URL
                $pathSegments = explode('/', trim($request->path(), '/'));
                if (isset($pathSegments[1])) {
                    $resourceName = $pathSegments[1];
                    return redirect("/admin/{$resourceName}");
                }
                
                return redirect('/admin');
            }
            
            throw $e;
        }
    }
}
