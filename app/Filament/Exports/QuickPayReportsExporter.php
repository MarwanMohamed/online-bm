<?php

namespace App\Filament\Exports;

use App\Models\QuickPay;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class QuickPayReportsExporter extends Exporter
{
    protected static ?string $model = QuickPay::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('created_at')->label('Date')
                ->state(fn($record) => date('d/m/Y', strtotime($record->created_at))),
            ExportColumn::make('ref_no')->label('Ref #'),
            ExportColumn::make('name'),
            ExportColumn::make('amount')->label('Amount'),
            ExportColumn::make('user.name')->label('Agent'),
            ExportColumn::make('status')->getStateUsing(fn($record) => $record->status == 0 ? 'Paid' : 'Unpaid')

        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your quick pay reports export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
