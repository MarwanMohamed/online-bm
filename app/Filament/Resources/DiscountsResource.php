<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DiscountsResource\Pages;
use App\Filament\Resources\DiscountsResource\RelationManagers;
use App\Models\Discount;
use App\Models\Discounts;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Toggle;

class DiscountsResource extends Resource
{
    protected static ?string $model = Discount::class;
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationGroup = 'Settings';
    protected static ?string $navigationIcon = 'phosphor-percent';

    public static function canAccess(): bool
    {
        return \Auth::user()->hasPermissionTo('General Settings');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('percent')->columnSpanFull(),
                Toggle::make('status')->label('status')->inline(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\IconColumn::make('status')
                    ->icon(fn(string $state): string => match ($state) {
                        '1' => 'heroicon-o-check',
                        '0' => 'heroicon-o-x-mark',
                    })
                    ->color(fn(string $state): string => match ($state) {
                        '0' => 'danger',
                        '1' => 'success',
                    }),

                Tables\Columns\TextColumn::make('percent')
                    ->getStateUsing(fn($record) => $record->percent . '%'),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListDiscounts::route('/'),
            'create' => Pages\CreateDiscounts::route('/create'),
            'edit' => Pages\EditDiscounts::route('/{record}/edit'),
        ];
    }
}
