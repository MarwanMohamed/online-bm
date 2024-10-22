<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    public function quickPay()
    {
        return $this->hasOne(Quickpay::class, 'ref_no', 'policy_ref');
    }

    public function insurance()
    {
        return $this->hasOne(Insurance::class, 'policy_id', 'policy_ref');
    }
}
