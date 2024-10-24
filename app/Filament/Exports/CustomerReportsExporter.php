<?php

namespace App\Filament\Exports;

use App\Models\CustomerReports;
use App\Models\Insurance;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Database\Eloquent\Builder;

class CustomerReportsExporter extends Exporter
{
    protected static ?string $model = Insurance::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('name')->label('Name'),
            ExportColumn::make('qid')->label('QID'),
            ExportColumn::make('email'),
            ExportColumn::make('mobile'),
            ExportColumn::make('getArea.area')->label('Area'),
        ];
    }

    public static function modifyQuery(Builder $query): Builder
    {
        return Insurance::query()
            ->where('deleted', 0)
            ->with('getArea')
            ->orderBy('created_at', 'DESC');
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your customer reports export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
