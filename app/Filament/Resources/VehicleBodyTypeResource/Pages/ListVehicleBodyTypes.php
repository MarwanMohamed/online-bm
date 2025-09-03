<?php

namespace App\Filament\Resources\VehicleBodyTypeResource\Pages;

use App\Filament\Resources\VehicleBodyTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVehicleBodyTypes extends ListRecords
{
    protected static string $resource = VehicleBodyTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
