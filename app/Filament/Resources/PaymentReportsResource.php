<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerReportsResource\Pages;
use App\Models\Transaction;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PaymentReportsResource extends Resource
{
    protected static ?string $model = Transaction::class;
    protected static ?string $label = 'Payment Reports';
    protected static ?string $navigationGroup = 'Reports';
    protected static ?int $navigationSort = 4;

    protected static ?string $navigationIcon = 'phosphor-table';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('status', '!=', 'Pending')->with('quickPay', 'insurance');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('policy_ref')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('insurance.vendor_policy_no')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('insurance.vhl_reg_no')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('amount')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('trans_key')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('transaction_no')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('status')->sortable()->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
//                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
//                Tables\Actions\BulkActionGroup::make([
//                    Tables\Actions\DeleteBulkAction::make(),
//                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPaymentReports::route('/'),
            'create' => Pages\CreatePaymentReports::route('/create'),
            'edit' => Pages\EditPaymentReports::route('/{record}/edit'),
        ];
    }
}
