<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuickpayReportsResource\Pages;
use App\Filament\Resources\QuickpayReportsResource\RelationManagers;
use App\Models\Quickpay;
use App\Models\QuickpayReports;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class QuickpayReportsResource extends Resource
{
    protected static ?string $model = Quickpay::class;
    protected static ?string $label = 'Quickpay Reports';
    protected static ?string $navigationGroup = 'Reports';
    protected static ?int $navigationSort = 3;

    protected static ?string $navigationIcon = 'phosphor-table';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('deleted', 0)
            ->with('user')
            ->orderBy('created_at', 'desc');
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
                Tables\Columns\TextColumn::make('created_at')->formatStateUsing(function ($state) {
                    return Carbon::parse($state)->format('d/m/Y');
                })->sortable()->searchable()->label('Date'),
                Tables\Columns\TextColumn::make('ref_no')
                     ->url(function ($record) {
                            return 'quick-pays/' . $record->id . '/edit';
                    })
                    ->sortable()->searchable()->label('Ref #'),
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('amount')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('user.name')->sortable()->searchable()->label('Agent'),
                TextColumn::make('status')->label('Status')
                    ->badge()
                    ->getStateUsing(fn($record) => $record->status == 0 ? 'Paid' : 'Unpaid')
                    ->color(fn(string $state): string => match ($state) {
                        'Paid' => 'success',
                        'Unpaid' => 'danger',
                    })
                    ->searchable()->sortable(),
            ])
            ->filters([
                SelectFilter::make('created_by')->label('Agent')
                    ->options(User::get()->pluck('name', 'id')),

                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from')->label('Date From')->displayFormat('d-m-Y')
                            ->placeholder('dd-mm-yyyy')
                            ->native(false),
                        DatePicker::make('created_until')->label('Date To')->displayFormat('d-m-Y')
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
            'index' => Pages\ListQuickpayReports::route('/'),
            'create' => Pages\CreateQuickpayReports::route('/create'),
//            'edit' => Pages\EditQuickpayReports::route('/{record}/edit'),
        ];
    }
}
