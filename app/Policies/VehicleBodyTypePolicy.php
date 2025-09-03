<?php

namespace App\Policies;

use App\Models\User;

class VehicleBodyTypePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('Vehicles Body Types Management');
    }
}
