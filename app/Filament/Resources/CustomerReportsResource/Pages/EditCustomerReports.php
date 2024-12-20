<?php

namespace App\Filament\Resources\CustomerReportsResource\Pages;

use App\Filament\Resources\CustomerReportsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCustomerReports extends EditRecord
{
    protected static string $resource = CustomerReportsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
