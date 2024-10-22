<?php

namespace App\Observers;

use App\Models\User;
use App\Exceptions\BadRequestException;

class UserObserver
{
    public function deleted(User $user): void
    {
        createLog("User " . $user->email . " Deleted by User: Deleted by User:" . \Auth::user()->name);
    }
}
