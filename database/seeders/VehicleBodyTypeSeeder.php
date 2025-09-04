<?php

namespace Database\Seeders;

use App\Models\VehicleBodyType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VehicleBodyTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bodyTypes = [
            'Sedan',
            'Hatchback', 
            'SUV',
            'Coupe',
            'Convertible',
            'Wagon',
            'Pickup Truck',
            'Van',
            'Minivan',
            'Crossover'
        ];

        foreach ($bodyTypes as $bodyType) {
            VehicleBodyType::firstOrCreate([
                'name' => $bodyType,
                'active' => true
            ]);
        }
    }
}
