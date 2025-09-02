<?php

namespace App\Filament\Resources\PermissionResource\Pages;

use Althinect\FilamentSpatieRolesPermissions\Resources\PermissionResource\Pages\EditPermission as DefaultEditPermission;
use App\Filament\Resources\PermissionResource;


/*
|--------------------------------------------------------------------------
| EditPermission Class
|--------------------------------------------------------------------------
|
| Represents the editing of a permission and extends the DefaultEditPermission class.
| It specifies the resource associated with this edit operation.
|
*/

class EditPermission extends DefaultEditPermission
{
    /*
    |--------------------------------------------------------------------------
    | Resource
    |--------------------------------------------------------------------------
    |
    | The resource associated with this edit operation.
    |
    */
    protected static string $resource = PermissionResource::class;
}
