<?php

namespace App\Policies;

use App\Models\User;

class VehicleColorPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('Vehicles Colors Management');
    }
}
