<?php

namespace App\Filament\Resources\CustomerReportsResource\Pages;

use App\Filament\Resources\CustomerReportsResource;
use App\Filament\Resources\PaymentReportsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPaymentReports extends ListRecords
{
    protected static string $resource = PaymentReportsResource::class;

    protected function getHeaderActions(): array
    {
        return [
//            Actions\CreateAction::make(),
        ];
    }
}
