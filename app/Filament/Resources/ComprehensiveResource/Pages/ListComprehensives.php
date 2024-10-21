<?php

namespace App\Filament\Resources\ComprehensiveResource\Pages;

use App\Filament\Resources\ComprehensiveResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListComprehensives extends ListRecords
{
    protected static string $resource = ComprehensiveResource::class;

    protected function getHeaderActions(): array
    {
        return [
//            Actions\CreateAction::make(),
        ];
    }
}
