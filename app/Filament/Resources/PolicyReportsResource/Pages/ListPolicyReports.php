<?php

namespace App\Filament\Resources\PolicyReportsResource\Pages;

use App\Filament\Resources\PolicyReportsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPolicyReports extends ListRecords
{
    protected static string $resource = PolicyReportsResource::class;

    protected function getHeaderActions(): array
    {
        return [
//            Actions\CreateAction::make(),
        ];
    }
}
