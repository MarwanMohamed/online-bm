<?php

namespace App\Policies;

use App\Models\User;

class VehicleModelPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('Vehicles Models Management');
    }
}
