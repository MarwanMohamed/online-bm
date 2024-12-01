<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Thirdparty extends Model
{
    use HasFactory;

    protected $table = 'thirdparty';

    protected $guarded = ['id', 'created_at', 'updated_at'];
}
