<?php

namespace App\Filament\Resources\CustomerReportsResource\Pages;

use App\Filament\Resources\PaymentReportsResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePaymentReports extends CreateRecord
{
    protected static string $resource = PaymentReportsResource::class;
}
