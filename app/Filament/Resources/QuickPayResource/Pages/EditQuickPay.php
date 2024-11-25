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

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['status'] = $data['status'] == 'Paid' ? 0 : 1;
        $data['created_by'] = Auth()->id();
        return $data;
    }
}
