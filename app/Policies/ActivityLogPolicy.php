<?php

namespace App\Policies;

use App\Models\User;

class ActivityLogPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('Activity Logs List');
    }
}
