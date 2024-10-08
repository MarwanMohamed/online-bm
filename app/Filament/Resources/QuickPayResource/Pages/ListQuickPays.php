<?php

namespace App\Filament\Resources\QuickPayResource\Pages;

use App\Filament\Resources\QuickPayResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListQuickPays extends ListRecords
{
    protected static string $resource = QuickPayResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
