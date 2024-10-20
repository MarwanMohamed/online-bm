<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerReportsResource\Pages;
use App\Models\Insurance;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
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
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('qid')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('email')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('mobile')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('getArea.area')->label('Area')->sortable()->searchable(),
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
            'index' => Pages\ListCustomerReports::route('/'),
            'create' => Pages\CreateCustomerReports::route('/create'),
            'edit' => Pages\EditCustomerReports::route('/{record}/edit'),
        ];
    }
}
