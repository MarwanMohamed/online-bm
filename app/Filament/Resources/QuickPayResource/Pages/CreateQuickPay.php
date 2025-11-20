<?php

namespace App\Filament\Resources\QuickPayResource\Pages;

use App\Filament\Resources\QuickPayResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateQuickPay extends CreateRecord
{
    protected static string $resource = QuickPayResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = Auth()->id();
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
