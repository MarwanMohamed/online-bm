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
                ->action(function () {
                    $quickpay = $this->record;
                    $transaction = Transaction::where('policy_ref', $quickpay->ref_no)
                        ->where('status', 'Payment processed successfully')
                        ->first();
                    
                    $pdf = PDF::loadView('pdf.quickpay-receipt', [
                        'quickpay' => $quickpay,
                        'transaction' => $transaction,
                    ]);
                    
                    return response()->streamDownload(function () use ($pdf) {
                        echo $pdf->stream();
                    }, 'quickpay-receipt-' . $quickpay->ref_no . '.pdf');
                })
                ->visible(fn () => $this->record && $this->record->status == 0),
            Actions\DeleteAction::make(),
        ];
    }

    protected function getFormActions(): array
    {
        return [
            Actions\Action::make('save')
                ->label(__('filament-panels::resources/pages/edit-record.form.actions.save.label'))
                ->submit('save'),

            Actions\Action::make('print')
                ->label('Print')
                ->icon('heroicon-o-printer')
                ->color('info')
                ->action(function () {
                    $quickpay = $this->record;
                    $transaction = Transaction::where('policy_ref', $quickpay->ref_no)
//                        ->where('status', 'Payment processed successfully')
                        ->first();

                    $pdf = PDF::loadView('pdf.quickpay-receipt', [
                        'quickpay' => $quickpay,
                        'transaction' => $transaction,
                    ]);

                    return response()->streamDownload(function () use ($pdf) {
                        echo $pdf->stream();
                    }, 'quickpay-receipt-' . $quickpay->ref_no . '.pdf');
                }),
//                ->visible(fn () => $this->record && $this->record->status == 0), // Only show when payment is processed successfully

        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['status'] = $data['status'] == 'Paid' ? 0 : 1;
        $data['created_by'] = Auth()->id();
        return $data;
    }
}
