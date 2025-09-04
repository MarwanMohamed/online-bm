<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VehicleModelResource\Pages;
use App\Models\VehicleModel;
use App\Models\Vehicle;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class VehicleModelResource extends Resource
{
    protected static ?string $model = VehicleModel::class;
    protected static ?string $navigationGroup = 'Manage Vehicles';
    protected static ?string $navigationIcon = 'phosphor-car-profile-light';
    protected static ?int $navigationSort = 3;
    protected static ?string $label = 'Vehicle Models';

    public static function canAccess(): bool
    {
        return \Auth::user()->hasPermissionTo('Vehicles Models Management');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('model_name')
                    ->label('Model Name')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Select::make('make_id')
                    ->label('Vehicle')
                    ->options(Vehicle::where('active', 1)->pluck('name', 'id'))
                    ->searchable()
                    ->required()
                    ->placeholder('Select a vehicle')
                    ->native(false)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->searchable()->sortable()->label('ID'),
                Tables\Columns\TextColumn::make('model_name')->label('Model Name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('vehicle.name')->label('Vehicle')->searchable()->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('make_id')
                    ->label('Vehicle')
                    ->options(function () {
                        return Vehicle::where('active', 1)->pluck('name', 'id')->toArray();
                    }),
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
            'index' => Pages\ListVehicleModels::route('/'),
            'create' => Pages\CreateVehicleModel::route('/create'),
            'edit' => Pages\EditVehicleModel::route('/{record}/edit'),
        ];
    }
}
