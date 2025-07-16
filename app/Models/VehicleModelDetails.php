<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleModelDetails extends Model
{
    use HasFactory;

    protected $table = 'vehicle_model_details';

    protected $guarded = ['id', 'created_at', 'updated_at'];

}
