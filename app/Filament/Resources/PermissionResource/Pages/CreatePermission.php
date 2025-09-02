<?php

namespace App\Filament\Resources\PermissionResource\Pages;

use Althinect\FilamentSpatieRolesPermissions\Resources\PermissionResource\Pages\CreatePermission as DefaultCreatePermission;
use App\Filament\Resources\PermissionResource;

/*
|--------------------------------------------------------------------------
| CreatePermission Class
|--------------------------------------------------------------------------
|
| Represents the creation of a permission and extends the DefaultCreatePermission class.
| It specifies the resource associated with this creation operation.
|
*/

class CreatePermission extends DefaultCreatePermission
{
    /*
    |--------------------------------------------------------------------------
    | Resource
    |--------------------------------------------------------------------------
    |
    | The resource associated with this creation operation.
    |
    */
    protected static string $resource = PermissionResource::class;
}
