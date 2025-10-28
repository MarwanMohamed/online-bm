<?php

namespace App\Filament\Imports;

use App\Models\Insurance;
use App\Models\Renewal;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class RenewalImporter extends Importer
{
    protected static ?string $model = Insurance::class;

//    protected static ?string $permission = 'import Workspace';

    public static function getColumns(): array
    {
        $columns = [
//            ImportColumn::make('id')
//                ->label('Id')
//                ->requiredMapping()
//                ->rules(['required']),
            ImportColumn::make('start_date')
                ->label('Date')
                ->requiredMapping()
                ->rules(['required']),

            ImportColumn::make('policy_id')
                ->label('Reference #')
                ->requiredMapping()
                ->rules(['required']),

            ImportColumn::make('name')
                ->label('Name')
                ->requiredMapping()
                ->rules(['required']),

            ImportColumn::make('qid')
                ->label('Qatar ID')
                ->requiredMapping()
                ->rules(['required']),

            ImportColumn::make('status')
                ->label('Policy Status')
                ->requiredMapping()
                ->rules(['required']),
        ];

        return $columns;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your Renewal import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}