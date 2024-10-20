<?php

namespace App\Filament\Resources\CustomerReportsResource\Pages;

use App\Filament\Resources\PaymentReportsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPaymentReports extends EditRecord
{
    protected static string $resource = PaymentReportsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
