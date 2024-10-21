<?php

namespace App\Filament\Resources\QuickpayReportsResource\Pages;

use App\Filament\Resources\QuickpayReportsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditQuickpayReports extends EditRecord
{
    protected static string $resource = QuickpayReportsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
