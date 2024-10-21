<?php

namespace App\Filament\Resources\RenewalManagementResource\Pages;

use App\Filament\Exports\RenewalExporter;
use App\Filament\Imports\RenewalImporter;
use App\Filament\Resources\RenewalManagementResource;
use Filament\Actions\ExportAction;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListRenewalManagement extends ListRecords
{
    protected static string $resource = RenewalManagementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ExportAction::make()->label('Export')
                ->exporter(RenewalExporter::class)->formats([
                    ExportFormat::Csv,
                    ExportFormat::Xlsx,
                ])
                ->modifyQueryUsing(function (Builder $query) {
                    return $this->getTableRecords()->toQuery();
                })
                ->icon('heroicon-o-arrow-up-on-square'),
                //->visible(Auth::user()->hasPermissionTo('export Workspace'))

            ImportAction::make()->importer(RenewalImporter::class)->label('Import')
                ->icon('heroicon-o-arrow-down-on-square'),
                //->visible(Auth::user()->hasPermissionTo('import Workspace')),

        ];
    }
}
