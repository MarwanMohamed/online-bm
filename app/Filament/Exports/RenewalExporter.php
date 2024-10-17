<?php

namespace App\Filament\Exports;

use App\Models\Insurance;
use App\Models\Renewal;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class RenewalExporter extends Exporter
{
    protected static ?string $model = Insurance::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')->label('ID'),

            ExportColumn::make('start_date')->label('Date')
                ->state(fn($record) => date('d/m/Y h:i A', strtotime($record->start_date))),

            ExportColumn::make('policy_id')->label('Reference #'),
            ExportColumn::make('name')->label('Name'),
            ExportColumn::make('qid')->label('Qatar ID'),

            ExportColumn::make('getStatus.status')->label('Policy Status'),

            ExportColumn::make('ad_verified')->label('Commit')
                ->state(function ($record) {
                    return $record->ad_verified == 'YES' ? 'Yes' : 'No';
                }),
            ExportColumn::make('user.name')
                ->state(fn($record) => isset($record->user) ? $record->user->name . ' on ' . $record->ad_verify_date : 'New')
                ->label('Commit By'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your renewal export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
