<?php

namespace App\Imports;

use App\Models\Vehicle;
use App\Models\VehicleModel;
use App\Models\VehicleModelDetails;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class VehiclesImport implements ToModel, withHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {

        $vehicleName = $row['make'];
        $modelName = $row['model'];
        $vehicle = Vehicle::whereRaw('LOWER(name) = ?', [strtolower($vehicleName)])->first();
        $model = VehicleModel::whereRaw('LOWER(model_name) = ?', [strtolower($modelName)])->first();

        if (!$vehicle) {
            $vehicle = Vehicle::create([
                'name' => $vehicleName,
                'active' => 1,
            ]);
        }

        if (!$model) {
            $model = VehicleModel::create([
                'model_name' => $modelName,
                'make_id' => $vehicle->id,
            ]);
        }

        return VehicleModelDetails::create([
            'model_id' => $model->id,
            'year' => $row['year'],
            'details' => $row['model_details'],
            'cylinder' => $row['cylinder'],
            'seats' => $row['seats'],
            'type' => $row['type'],
            'premium' => $row['premium']
        ]);

    }
}
