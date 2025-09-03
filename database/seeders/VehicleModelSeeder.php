<?php

namespace Database\Seeders;

use App\Models\Vehicle;
use App\Models\VehicleModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VehicleModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إضافة بعض الماركات أولاً
        $vehicles = [
            'Toyota',
            'Honda', 
            'BMW',
            'Mercedes-Benz',
            'Audi',
            'Nissan',
            'Hyundai',
            'Kia',
            'Ford',
            'Chevrolet'
        ];

        foreach ($vehicles as $vehicleName) {
            Vehicle::firstOrCreate([
                'name' => $vehicleName,
                'active' => true
            ]);
        }

        // إضافة نماذج للسيارات
        $models = [
            ['Toyota', ['Camry', 'Corolla', 'RAV4', 'Highlander', 'Prius']],
            ['Honda', ['Civic', 'Accord', 'CR-V', 'Pilot', 'Fit']],
            ['BMW', ['3 Series', '5 Series', 'X3', 'X5', 'i3']],
            ['Mercedes-Benz', ['C-Class', 'E-Class', 'S-Class', 'GLC', 'GLE']],
            ['Audi', ['A3', 'A4', 'A6', 'Q3', 'Q5']],
            ['Nissan', ['Altima', 'Sentra', 'Rogue', 'Murano', 'Pathfinder']],
            ['Hyundai', ['Elantra', 'Sonata', 'Tucson', 'Santa Fe', 'Accent']],
            ['Kia', ['Forte', 'Optima', 'Sorento', 'Sportage', 'Soul']],
            ['Ford', ['Focus', 'Fusion', 'Escape', 'Explorer', 'F-150']],
            ['Chevrolet', ['Cruze', 'Malibu', 'Equinox', 'Traverse', 'Silverado']]
        ];

        foreach ($models as [$vehicleName, $modelNames]) {
            $vehicle = Vehicle::where('name', $vehicleName)->first();
            if ($vehicle) {
                foreach ($modelNames as $modelName) {
                    VehicleModel::firstOrCreate([
                        'model_name' => $modelName,
                        'make_id' => $vehicle->id,
                        'active' => true
                    ]);
                }
            }
        }
    }
}
