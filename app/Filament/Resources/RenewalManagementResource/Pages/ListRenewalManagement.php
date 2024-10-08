<?php

namespace App\Filament\Resources\RenewalManagementResource\Pages;

use App\Filament\Resources\RenewalManagementResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRenewalManagement extends ListRecords
{
    protected static string $resource = RenewalManagementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
