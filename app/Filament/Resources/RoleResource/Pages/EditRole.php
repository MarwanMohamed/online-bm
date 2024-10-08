<?php

namespace App\Filament\Resources\RoleResource\Pages;

use Althinect\FilamentSpatieRolesPermissions\Resources\RoleResource\Pages\EditRole as DefaultEditRoleRole;
use App\Filament\Resources\RoleResource;


/*
|--------------------------------------------------------------------------
| EditRole Class
|--------------------------------------------------------------------------
|
| Represents the editing of a role and extends the DefaultEditRoleRole class.
| It specifies the resource associated with this edit operation.
|
*/

class EditRole extends DefaultEditRoleRole
{
    /*
    |--------------------------------------------------------------------------
    | Resource
    |--------------------------------------------------------------------------
    |
    | The resource associated with this edit operation.
    |
    */
    protected static string $resource = RoleResource::class;
}
