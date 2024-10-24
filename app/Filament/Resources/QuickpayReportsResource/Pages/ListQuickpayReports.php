<?php

namespace App\Filament\Resources\QuickpayReportsResource\Pages;

use App\Filament\Exports\QuickPayReportsExporter;
use App\Filament\Resources\QuickpayReportsResource;
use App\Models\Quickpay;
use Barryvdh\DomPDF\Facade\Pdf;
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
            Actions\Action::make('pdf')->label('Export PDF')->action(function (Builder $query) {
                $ids = collect($this->getTableRecords()->items())->pluck('id');
                $data = Quickpay::query()->whereIn('id', $ids)->get();
                $pdf = PDF::loadView('pdf.quickpay-reports', compact('data'));
                return response()->streamDownload(function () use ($pdf) {
                    echo $pdf->stream();
                }, 'Quickpay-reports.pdf');
            })->icon('heroicon-o-arrow-up-on-square'),

            ExportAction::make()->label('Export Excel')
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
