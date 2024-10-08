<?php

namespace App\Filament\Resources\RoleResource\Pages;

use Althinect\FilamentSpatieRolesPermissions\Resources\RoleResource\Pages\CreateRole as DefaultCreateRole;
use App\Filament\Resources\RoleResource;

/*
|--------------------------------------------------------------------------
| CreateRole Class
|--------------------------------------------------------------------------
|
| Represents the creation of a role and extends the DefaultCreateRole class.
| It specifies the resource associated with this creation operation.
|
*/

class CreateRole extends DefaultCreateRole
{
    /*
    |--------------------------------------------------------------------------
    | Resource
    |--------------------------------------------------------------------------
    |
    | The resource associated with this creation operation.
    |
    */
    protected static string $resource = RoleResource::class;
}
