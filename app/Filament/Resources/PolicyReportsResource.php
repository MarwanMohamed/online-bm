<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PolicyReportsResource\Pages;
use App\Filament\Resources\PolicyReportsResource\RelationManagers;
use App\Models\Company;
use App\Models\Insurance;
use App\Models\PolicyReports;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PolicyReportsResource extends Resource
{
    protected static ?string $model = Insurance::class;
    protected static ?string $label = 'Policy Reports';
    protected static ?string $navigationGroup = 'Reports';
    protected static ?int $navigationSort = 3;

    protected static ?string $navigationIcon = 'phosphor-table';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('deleted', 0)
            ->with('getStatus', 'company', 'user', 'transaction');
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
                Tables\Columns\TextColumn::make('created_at')
                    ->formatStateUsing(function ($state) {
                        return Carbon::parse($state)->format('d/m/Y');
                    })->sortable()->searchable()->label('Date'),
                Tables\Columns\TextColumn::make('policy_id')
                    ->url(function ($record) {
                            return 'insurances/' . $record->id . '/edit';
                    })->sortable()->searchable()->label('Ref #'),
                Tables\Columns\TextColumn::make('ins_type')->sortable()->searchable()->label('Type'),
                Tables\Columns\TextColumn::make('company.name')->sortable()->searchable()->label('Insurance company'),
                Tables\Columns\TextColumn::make('transaction.amount')->sortable()->searchable()->label('Amount'),
                Tables\Columns\TextColumn::make('user.name')->sortable()->searchable()->label('Agent'),
                Tables\Columns\TextColumn::make('getStatus.status')->label('Status')
                    ->badge()->searchable()->sortable()
                    ->color(fn(string $state): string => match ($state) {
                        'To Renew', 'Verification', 'Expired', 'Lost' => 'danger',
                        'Paid' => 'info',
                        'Issued' => 'success',
                        'In Progress' => 'warning',
                        'Refunded' => 'gray',
                    }),
                Tables\Columns\TextColumn::make('transaction.status')->sortable()->searchable()->label('Payment Status'),
            ])
            ->filters([
                SelectFilter::make('ad_id')->label('Agent')
                    ->options(User::get()->pluck('name', 'id')),

                SelectFilter::make('com_id')->label('Insurance Provider')
                    ->options(Company::get()->pluck('name', 'id')),

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
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
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
            'index' => Pages\ListPolicyReports::route('/'),
            'create' => Pages\CreatePolicyReports::route('/create'),
//            'edit' => Pages\EditPolicyReports::route('/{record}/edit'),
        ];
    }
}
