<?php

namespace App\Filament\Resources\PermissionResource\Pages;

use Althinect\FilamentSpatieRolesPermissions\Resources\PermissionResource\Pages\ViewPermission as DefaultViewPermission;
use App\Filament\Resources\PermissionResource;


/*
|--------------------------------------------------------------------------
| ViewPermission Class
|--------------------------------------------------------------------------
|
| Represents the viewing of a permission and extends the DefaultViewPermission class.
| It specifies the resource associated with this view operation.
|
*/

class ViewPermission extends DefaultViewPermission
{
    /*
    |--------------------------------------------------------------------------
    | Resource
    |--------------------------------------------------------------------------
    |
    | The resource associated with this view operation.
    |
    */
    protected static string $resource = PermissionResource::class;
}
