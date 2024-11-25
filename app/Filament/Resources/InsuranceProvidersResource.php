<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InsuranceProvidersResource\Pages;
use App\Filament\Resources\InsuranceProvidersResource\RelationManagers;
use App\Models\Company;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Table;

class InsuranceProvidersResource extends Resource
{
    protected static ?string $model = Company::class;
    protected static ?string $navigationIcon = 'phosphor-images';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationGroup = 'Settings';
    protected static ?string $label = 'Insurance Providers';

    public static function canAccess(): bool
    {
        return \Auth::user()->hasPermissionTo('General Settings');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->columnSpanFull()->required(),
                SpatieMediaLibraryFileUpload::make('logo'),
                Toggle::make('active')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('name')->searchable(),
                SpatieMediaLibraryImageColumn::make('logo'),
                Tables\Columns\ToggleColumn::make('active')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListInsuranceProviders::route('/'),
            'create' => Pages\CreateInsuranceProviders::route('/create'),
//            'edit' => Pages\EditInsuranceProviders::route('/{record}/edit'),
        ];
    }
}
