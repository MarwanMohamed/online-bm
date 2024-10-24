<?php

namespace App\Filament\Resources\PolicyReportsResource\Pages;

use App\Filament\Exports\PolicyReportsExporter;
use App\Filament\Resources\PolicyReportsResource;
use App\Models\Insurance;
use Barryvdh\DomPDF\Facade\Pdf;
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
            Actions\Action::make('pdf')->label('Export PDF')->action(function (Builder $query) {
                $ids = collect($this->getTableRecords()->items())->pluck('id');
                $data = Insurance::query()->whereIn('id', $ids)->get();
                $pdf = PDF::loadView('pdf.policy-reports', compact('data'));
                return response()->streamDownload(function () use ($pdf) {
                    echo $pdf->stream();
                }, 'policy-reports.pdf');
            })->icon('heroicon-o-arrow-up-on-square'),

            ExportAction::make()->label('Export Excel')
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
