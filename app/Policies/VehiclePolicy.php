<?php

namespace App\Policies;

use App\Models\User;

class VehiclePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('Vehicles Management');
    }
}
