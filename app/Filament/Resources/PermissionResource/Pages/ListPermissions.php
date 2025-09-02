<?php

namespace App\Filament\Resources\PermissionResource\Pages;

use Althinect\FilamentSpatieRolesPermissions\Resources\PermissionResource\Pages\ListPermissions as DefaultListPermissions;
use App\Filament\Resources\PermissionResource;


/*
|--------------------------------------------------------------------------
| ListPermissions Class
|--------------------------------------------------------------------------
|
| Represents the listing of permissions and extends the DefaultListPermissions class.
| It specifies the resource associated with this list operation.
|
*/

class ListPermissions extends DefaultListPermissions
{
    /*
    |--------------------------------------------------------------------------
    | Resource
    |--------------------------------------------------------------------------
    |
    | The resource associated with this list operation.
    |
    */
    protected static string $resource = PermissionResource::class;
}
