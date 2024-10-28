<?php

namespace App\Policies;

use App\Models\Insurance;
use App\Models\User;

class InsurancePolicy
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
        return $user->hasPermissionTo('Insurance List');
    }

    /*
    |--------------------------------------------------------------------------
    | View User
    |--------------------------------------------------------------------------
    |
    | Determine whether the user can view the specific bank model.
    |
    */
    public function view(User $user, Insurance $insurance): bool
    {
        return $user->hasPermissionTo('Insurance View');
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
        return $user->hasPermissionTo('Add Users');
    }

    /*
    |--------------------------------------------------------------------------
    | Update User
    |--------------------------------------------------------------------------
    |
    | Determine whether the user can update the specific bank model.
    |
    */
    public function update(User $user, Insurance $insurance): bool
    {
        return $user->hasPermissionTo('Insurance Edit');
    }

    /*
    |--------------------------------------------------------------------------
    | Delete User
    |--------------------------------------------------------------------------
    |
    | Determine whether the user can delete the specific bank model.
    |
    */
    public function delete(User $user, Insurance $insurance): bool
    {
        return $user->hasPermissionTo('Insurance Delete');
    }
}
