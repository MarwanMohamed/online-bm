<?php

namespace App\Filament\Resources\CustomerReportsResource\Pages;

use App\Filament\Exports\CustomerReportsExporter;
use App\Filament\Resources\CustomerReportsResource;
use App\Models\Insurance;
use Filament\Actions;
use Filament\Actions\ExportAction;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListCustomerReports extends ListRecords
{
    protected static string $resource = CustomerReportsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ExportAction::make()->label('Export')
                ->exporter(CustomerReportsExporter::class)->formats([
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
