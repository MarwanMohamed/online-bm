<?php

namespace App\Filament\Resources\InsuranceProvidersResource\Pages;

use App\Filament\Resources\InsuranceProvidersResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInsuranceProviders extends ListRecords
{
    protected static string $resource = InsuranceProvidersResource::class;

    protected function getHeaderActions(): array
    {
        return [
//            Actions\CreateAction::make(),
        ];
    }
}
