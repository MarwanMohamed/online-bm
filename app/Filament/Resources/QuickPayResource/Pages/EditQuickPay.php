<?php

namespace App\Filament\Resources\QuickPayResource\Pages;

use App\Filament\Resources\QuickPayResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditQuickPay extends EditRecord
{
    protected static string $resource = QuickPayResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
