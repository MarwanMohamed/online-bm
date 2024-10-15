<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ComprehensiveResource\Pages;
use App\Models\Insurance;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;


class ComprehensiveResource extends Resource
{
    protected static ?string $model = Comprehensive::class;
    protected static ?int $navigationSort = 2;

    protected static ?string $navigationIcon = 'phosphor-article';

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
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
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
            'index' => Pages\ListComprehensives::route('/'),
            'create' => Pages\CreateComprehensive::route('/create'),
            'edit' => Pages\EditComprehensive::route('/{record}/edit'),
        ];
    }
}
