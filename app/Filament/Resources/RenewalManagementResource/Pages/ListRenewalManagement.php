<?php

namespace App\Filament\Resources\RenewalManagementResource\Pages;

use App\Filament\Exports\RenewalExporter;
use App\Filament\Imports\RenewalImporter;
use App\Filament\Resources\RenewalManagementResource;
use App\Imports\RenewalImport;
use App\Models\Insurance;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\ExportAction;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Actions\ImportAction;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Livewire\Notifications;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions;
use Maatwebsite\Excel\Facades\Excel;

class ListRenewalManagement extends ListRecords
{
    protected static string $resource = RenewalManagementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('pdf')->label('Export PDF')->action(function (Builder $query) {
                $ids = collect($this->getTableRecords()->items())->pluck('id');
                $data = Insurance::query()->whereIn('id', $ids)->get();
                $pdf = PDF::loadView('pdf.renewal', compact('data'));
                return response()->streamDownload(function () use ($pdf) {
                    echo $pdf->stream();
                }, 'renwal.pdf');
            })->icon('heroicon-o-arrow-up-on-square'),

            ExportAction::make()->label('Export Excel')
                ->exporter(RenewalExporter::class)->formats([
                    ExportFormat::Csv,
                    ExportFormat::Xlsx,
                ])
                ->columnMapping(false)
                ->icon('heroicon-o-arrow-up-on-square'),
            //->visible(Auth::user()->hasPermissionTo('export Workspace'))

//            ImportAction::make()->importer(RenewalImporter::class)->label('Import')
//                ->icon('heroicon-o-arrow-down-on-square'),
            //->visible(Auth::user()->hasPermissionTo('import Workspace')),

        ];
    }
}
