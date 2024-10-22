<?php

namespace App\Observers;

use App\Models\Insurance;

class InsuranceObserver
{
    public function deleted(Insurance $insurance)
    {
        createLog('Insurance ' . $insurance->policy_id . ' Deleted by User:' . \Auth::user()->name);
    }
}
