<?php

namespace App\Filament\Resources\QuickPayResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransactionsRelationManager extends RelationManager
{
    protected static string $relationship = 'transactions';
    protected static ?string $label = 'Payment Details';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('policy_ref')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('policy_ref')
            ->columns([
                Tables\Columns\TextColumn::make('policy_ref')->label('Ref #'),
                Tables\Columns\TextColumn::make('trans_key')->label('Transaction Key'),
                Tables\Columns\TextColumn::make('txn_type')->label('Transaction Type'),
                Tables\Columns\TextColumn::make('amount'),
                Tables\Columns\TextColumn::make('transaction_no')->label('Transaction #'),
                Tables\Columns\TextColumn::make('status')
                    ->formatStateUsing(function ($state, $record) {
                        return $record->refund_status == 1 ? '' : $state;
                    }),
                Tables\Columns\TextColumn::make('refund_status')
                    ->formatStateUsing(function ($state) {
                        return $state == 0 ? 'Not Refunded' : 'Refunded';
                    })
                    ->badge()
                    ->color(fn($state) => $state == 0 ? 'warning' : 'success'),])
            ->filters([
                //
            ])
            ->headerActions([
//                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\Action::make('refund')
                    ->label('Refund')
                    ->icon('heroicon-o-arrow-path')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Confirm Refund')
                    ->modalDescription('Are you sure you want to refund this transaction?')
                    ->action(function ($record) {
                        $record->update(['refund_status' => 1]);

                        \Filament\Notifications\Notification::make()
                            ->title('Transaction Refunded')
                            ->success()
                            ->send();
                    })
                    ->visible(fn ($record) => $record->refund_status == 0 && $record->txn_type == 'Debit'),
//                Tables\Actions\EditAction::make(),
//                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
