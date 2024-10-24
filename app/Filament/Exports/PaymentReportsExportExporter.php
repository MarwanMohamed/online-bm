<?php

namespace App\Filament\Exports;

use App\Models\PaymentReportsExport;
use App\Models\Transaction;
use Carbon\Carbon;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class PaymentReportsExportExporter extends Exporter
{
    protected static ?string $model = Transaction::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('date')->formatStateUsing(function ($state) {
                return Carbon::parse($state)->format('d/m/Y');
            }),
            ExportColumn::make('policy_ref')->label('Ref'),
            ExportColumn::make('quickPay.description')->label('Policy/Description'),
            ExportColumn::make('quickPay.ref_no')->label('Plate'),
            ExportColumn::make('amount')->label('Amount'),
            ExportColumn::make('trans_key')->label('Merchant TXN'),
            ExportColumn::make('transaction_no')->label('Bank TXN'),
            ExportColumn::make('status')->label('Response'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your payment reports export export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
