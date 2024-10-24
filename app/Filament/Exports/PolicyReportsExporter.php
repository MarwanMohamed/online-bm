<?php

namespace App\Filament\Exports;

use App\Models\Insurance;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class PolicyReportsExporter extends Exporter
{
    protected static ?string $model = Insurance::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('created_at')->label('Date')
                ->state(fn($record) => date('d/m/Y', strtotime($record->created_at))),
            ExportColumn::make('policy_id')->label('Ref #'),
            ExportColumn::make('ins_type')->label('Type'),
            ExportColumn::make('policy_id')->label('Ref #'),
            ExportColumn::make('company.name')->label('Insurance company'),
            ExportColumn::make('transaction.amount')->label('Amount'),
            ExportColumn::make('user.name')->label('Agent'),
            ExportColumn::make('getStatus.status')->label('Status'),
            ExportColumn::make('transaction.status')->label('Payment Status'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your policy reports export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
