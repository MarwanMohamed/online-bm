<?php

namespace App\Policies;

use App\Models\User;

class RolePolicy
{
    /*
    |--------------------------------------------------------------------------
    | View Any User
    |--------------------------------------------------------------------------
    |
    | Determine whether the user can view any bank models.
    |
    */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('Roles List');
    }

    /*
    |--------------------------------------------------------------------------
    | View User
    |--------------------------------------------------------------------------
    |
    | Determine whether the user can view the specific bank model.
    |
    */
    public function view(User $user): bool
    {
        return $user->hasPermissionTo('Roles List');
    }

    /*
    |--------------------------------------------------------------------------
    | Create User
    |--------------------------------------------------------------------------
    |
    | Determine whether the user can create a new bank model.
    |
    */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('Add Roles');
    }

    /*
    |--------------------------------------------------------------------------
    | Update User
    |--------------------------------------------------------------------------
    |
    | Determine whether the user can update the specific bank model.
    |
    */
    public function update(User $user): bool
    {
        return $user->hasPermissionTo('Edit Roles');
    }

    /*
    |--------------------------------------------------------------------------
    | Delete User
    |--------------------------------------------------------------------------
    |
    | Determine whether the user can delete the specific bank model.
    |
    */
    public function delete(User $user): bool
    {
        return $user->hasPermissionTo('Delete Users');
    }
}
