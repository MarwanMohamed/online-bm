<?php

namespace App\Filament\Resources\ComprehensiveResource\Pages;

use App\Filament\Resources\ComprehensiveResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditComprehensive extends EditRecord
{
    protected static string $resource = ComprehensiveResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
