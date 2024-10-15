<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quickpay extends Model
{
    use HasFactory;

    protected $table = 'quickpay';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function transactions()
    {
        return $this->belongsTo(Transaction::class, 'ref_no', 'policy_ref');
    }
}
