<?php

namespace App\Filament\Resources\RoleResource\Pages;

use Althinect\FilamentSpatieRolesPermissions\Resources\RoleResource\Pages\ListRoles as DefaultListRoles;
use App\Filament\Resources\RoleResource;


/*
|--------------------------------------------------------------------------
| ListRoles Class
|--------------------------------------------------------------------------
|
| Represents the listing of roles and extends the DefaultListRoles class.
| It specifies the resource associated with this list operation.
|
*/

class ListRoles extends DefaultListRoles
{
    /*
    |--------------------------------------------------------------------------
    | Resource
    |--------------------------------------------------------------------------
    |
    | The resource associated with this list operation.
    |
    */
    protected static string $resource = RoleResource::class;
}
