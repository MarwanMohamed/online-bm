<?php

namespace App\Filament\Resources\ManagePricingResource\Pages;

use App\Filament\Resources\ManagePricingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditManagePricing extends EditRecord
{
    protected static string $resource = ManagePricingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
