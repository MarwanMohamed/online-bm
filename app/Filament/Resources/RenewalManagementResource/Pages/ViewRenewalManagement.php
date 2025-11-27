<?php

namespace App\Filament\Resources\RenewalManagementResource\Pages;

use App\Filament\Resources\RenewalManagementResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use TessPayments\Checkout\CheckoutService;

class ViewRenewalManagement extends ViewRecord
{
    protected static string $resource = RenewalManagementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('refund')
                ->label('Refund')
                ->icon('heroicon-o-arrow-path')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Confirm Refund')
                ->modalDescription('Are you sure you want to refund this transaction?')
                ->action(function () {
                    $record = $this->record;
                    if(!$record->transaction){
                        return;
                    }
                    $renewal = $record;
                    $transaction = $record->transaction;
                    // Resolve the CheckoutService (or inject via constructor)
                    if('Payment processed successfully' === $transaction['status']){
                        $checkout = app(CheckoutService::class);

                        $params = [
                            'payment_id' => (string)$transaction['transaction_no'],
                            'amount' => (string)number_format($transaction['amount'], 2, '.', ''),
                        ];

                        $response = $checkout->refundPayment($params);
                        try {

                            if (isset($response['result']) && $response['result'] === 'accepted') {
                                $transaction->update(['refund_status' => '1']);
                                $renewal->update(['status' => '8']);
                                Notification::make()
                                    ->title('Refund Accepted')
                                    ->success()
                                    ->body('Payment ID: ' . $response['payment_id'])
                                    ->send();
                            } else {
                                Notification::make()
                                    ->title('Refund Failed')
                                    ->danger()
                                    ->body($response['message'] ?? 'Unknown error')
                                    ->send();
                            }

                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Refund Error')
                                ->danger()
                                ->body($e->getMessage())
                                ->send();
                        }
                    }
                })
                ->visible(fn() => $this->record->transaction && $this->record->transaction->refund_status == 0 && $this->record->transaction->txn_type == 'Other'),
                Actions\EditAction::make(),
            
                Actions\DeleteAction::make(),
        ];
    }
}

