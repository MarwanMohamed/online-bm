<?php

namespace App\Filament\Resources\QuickPayResource\Pages;

use App\Filament\Resources\QuickPayResource;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditQuickPay extends EditRecord
{
    protected static string $resource = QuickPayResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('print')
                ->label('Print E-Receipt')
                ->icon('heroicon-o-printer')
                ->color('info')
                ->url(fn () => route('quickpay.receipt', $this->record->id))
                ->openUrlInNewTab()
                ->visible(fn () => $this->record && $this->record->status == 0),
            Actions\DeleteAction::make(),
        ];
    }


    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['status'] = $data['status'] == 'Paid' ? 0 : 1;
        $data['created_by'] = Auth()->id();
        return $data;
    }
}
