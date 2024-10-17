<?php

namespace App\Filament\Resources\RenewalManagementResource\Pages;

use App\Filament\Exports\RenewalExporter;
use App\Filament\Resources\RenewalManagementResource;
use Filament\Actions;
use Filament\Actions\ExportAction;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListRenewalManagement extends ListRecords
{
    protected static string $resource = RenewalManagementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ExportAction::make()
                ->exporter(RenewalExporter::class)->formats([
                    ExportFormat::Csv,
                    ExportFormat::Xlsx,
                ])
                ->modifyQueryUsing(function (Builder $query) {
                    return $this->getTableRecords()->toQuery();
                })
                ->icon('heroicon-o-arrow-down-on-square')
//                ->visible(Auth::user()->hasPermissionTo('export Workspace'))
        ];
    }
}
