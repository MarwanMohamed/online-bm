<?php

namespace App\Filament\Resources\PolicyReportsResource\Pages;

use App\Filament\Exports\PolicyReportsExporter;
use App\Filament\Resources\PolicyReportsResource;
use Filament\Actions;
use Filament\Actions\ExportAction;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListPolicyReports extends ListRecords
{
    protected static string $resource = PolicyReportsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ExportAction::make()->label('Export')
                ->exporter(PolicyReportsExporter::class)->formats([
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
