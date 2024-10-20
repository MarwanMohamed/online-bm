<?php

namespace App\Filament\Resources\PolicyReportsResource\Pages;

use App\Filament\Resources\PolicyReportsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPolicyReports extends EditRecord
{
    protected static string $resource = PolicyReportsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
