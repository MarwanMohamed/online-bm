<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VehicleBodyTypeResource\Pages;
use App\Filament\Resources\VehicleBodyTypeResource\RelationManagers;
use App\Models\VehicleBodyType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VehicleBodyTypeResource extends Resource
{
    protected static ?string $model = VehicleBodyType::class;
    protected static ?string $navigationGroup = 'Manage Vehicles';
    protected static ?string $navigationIcon = 'phosphor-shapes-light';
    protected static ?int $navigationSort = 4;
    protected static ?string $label = 'Vehicle Body Types';

    public static function canAccess(): bool
    {
        return \Auth::user()->hasPermissionTo('Vehicles Body Types Management');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->label('Body Type Name')
                    ->required()->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->searchable()->sortable()->label('ID'),
                Tables\Columns\TextColumn::make('name')->label('Body Type')->searchable()->sortable(),
            ])
            ->filters([
//                Tables\Filters\TernaryFilter::make('active')
//                    ->label('Active')
//                    ->placeholder('All')
//                    ->trueLabel('Active')
//                    ->falseLabel('Inactive'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->defaultSort('id', 'desc')
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListVehicleBodyTypes::route('/'),
            'create' => Pages\CreateVehicleBodyType::route('/create'),
            'edit' => Pages\EditVehicleBodyType::route('/{record}/edit'),
        ];
    }
}
