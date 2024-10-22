<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerReportsResource\Pages;
use App\Models\Transaction;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\QueryBuilder\Constraints\DateConstraint;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PaymentReportsResource extends Resource
{
    protected static ?string $model = Transaction::class;
    protected static ?string $label = 'Payment Reports';
    protected static ?string $navigationGroup = 'Reports';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationIcon = 'phosphor-table';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('status', '!=', 'Pending')
            ->with('quickPay', 'insurance')
            ->orderBy('date', 'desc');
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
                Tables\Columns\TextColumn::make('date')->sortable()->searchable()
                    ->formatStateUsing(function ($state) {
                        return Carbon::parse($state)->format('d/m/Y');
                    }),
                Tables\Columns\TextColumn::make('policy_ref')->label('Ref')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('quickPay.description')->label('Policy/Description')->searchable(),
                Tables\Columns\TextColumn::make('quickPay.ref_no')->label('Plate')->searchable(),
                Tables\Columns\TextColumn::make('amount')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('trans_key')->label('Merchant TXN')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('transaction_no')->label('Bank TXN')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('status')->label('Response')->sortable()->searchable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'Approved' => 'Approved',
                        'Failed' => 'Failed'
                    ])
                    ->placeholder('Select Status')
                    ->query(function (Builder $query, array $data) {
                        if (isset($data['value']) && $data['value'] === 'Failed') {
                            $query->where('status', '!=', 'Approved');
                        }
                    }),

                SelectFilter::make('payment_gateway')
                    ->options([
                        'DB' => 'Credit Card',
                        'QCB' => 'Debit Card'
                    ])->placeholder('Select Payment Gateway'),
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from')->label('Date From')->displayFormat('d-m-Y')
                                  ->placeholder('dd-mm-yyyy')
                                  ->native(false),
                        DatePicker::make('created_until')->label('Date From')->displayFormat('d-m-Y')
                                  ->placeholder('dd-mm-yyyy')
                                  ->native(false),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('date', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('date', '<=', $date),
                            );
                    })
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
