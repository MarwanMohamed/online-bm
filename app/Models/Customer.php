<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;
    
    protected $table = 'customers';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * Get the insurances for the customer.
     */
    public function insurances(): HasMany
    {
        return $this->hasMany(Insurance::class, 'qid', 'qid');
    }
}
