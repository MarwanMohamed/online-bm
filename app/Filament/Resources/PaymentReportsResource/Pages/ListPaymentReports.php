<?php

namespace App\Filament\Resources\PaymentReportsResource\Pages;

use App\Filament\Exports\PaymentReportsExportExporter;
use App\Filament\Resources\PaymentReportsResource;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions;
use Filament\Actions\ExportAction;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListPaymentReports extends ListRecords
{
    protected static string $resource = PaymentReportsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('pdf')->label('Export PDF')->action(function (Builder $query) {
                $ids = collect($this->getTableRecords()->items())->pluck('id');
                $data = Transaction::query()->whereIn('id', $ids)->get();
                $pdf = PDF::loadView('pdf.payment-reports', compact('data'));
                return response()->streamDownload(function () use ($pdf) {
                    echo $pdf->stream();
                }, 'Payment-reports.pdf');
            })->icon('heroicon-o-arrow-up-on-square'),

            ExportAction::make()->label('Export Excel')
                ->exporter(PaymentReportsExportExporter::class)->formats([
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
