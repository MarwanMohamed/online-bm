<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerReportsResource\Pages;
use App\Models\Area;
use App\Models\Insurance;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CustomerReportsResource extends Resource
{
    protected static ?string $model = Insurance::class;
    protected static ?string $label = 'Customers Reports';
    protected static ?string $navigationGroup = 'Reports';
    protected static ?int $navigationSort = 4;

    protected static ?string $navigationIcon = 'phosphor-table';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->select(['id', 'name', 'qid', 'email', 'mobile', 'area'])
            ->where('deleted', 0)->with('getArea')->groupBy('qid');
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
                Tables\Columns\TextColumn::make('name')->sortable()->searchable()
                    ->url(fn($record) => 'insurances/' . $record->id . '/edit'),
                Tables\Columns\TextColumn::make('qid')->sortable()->searchable()->label('QID'),
                Tables\Columns\TextColumn::make('email')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('mobile')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('getArea.area')->label('Area')->sortable()->searchable(),
            ])
            ->filters([
                SelectFilter::make('area')->label('Area')
                    ->options(Area::get()->pluck('area', 'id')),

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
            'index' => Pages\ListCustomerReports::route('/'),
            'create' => Pages\CreateCustomerReports::route('/create'),
            'edit' => Pages\EditCustomerReports::route('/{record}/edit'),
        ];
    }
}
