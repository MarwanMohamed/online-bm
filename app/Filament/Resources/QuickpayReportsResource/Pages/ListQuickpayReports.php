<?php

namespace App\Filament\Resources\QuickpayReportsResource\Pages;

use App\Filament\Exports\QuickPayReportsExporter;
use App\Filament\Resources\QuickpayReportsResource;
use Filament\Actions;
use Filament\Actions\ExportAction;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListQuickpayReports extends ListRecords
{
    protected static string $resource = QuickpayReportsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ExportAction::make()->label('Export')
                ->exporter(QuickPayReportsExporter::class)->formats([
                    ExportFormat::Csv,
                    ExportFormat::Xlsx,
                ])
                ->modifyQueryUsing(function (Builder $query) {
                    return $query->whereIn('id', collect($this->getTableRecords()->items())->pluck('id'));
                })
                ->icon('heroicon-o-arrow-up-on-square'),
            //->visible(Auth::user()->hasPermissionTo('export Workspace'))
        ];
    }
}
