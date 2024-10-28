<?php

namespace App\Policies;

use App\Models\User;

class QuickpayPolicy
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
        return $user->hasPermissionTo('Quickpay List');
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
        return $user->hasPermissionTo('Quickpay View');
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
        return $user->hasPermissionTo('Quickpay Add');
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
        return $user->hasPermissionTo('Quickpay Edit');
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
        return $user->hasPermissionTo('Quickpay Delete');
    }
}
