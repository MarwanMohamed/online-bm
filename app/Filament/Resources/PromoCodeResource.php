<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PromoCodeResource\Pages;
use App\Filament\Resources\PromoCodeResource\RelationManagers;
use App\Models\Promocode;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PromoCodeResource extends Resource
{
    protected static ?string $model = Promocode::class;
    protected static ?string $navigationIcon = 'heroicon-o-receipt-percent';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationGroup = 'Settings';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('code')->required(),
                TextInput::make('amount')->required()->numeric(),
                Select::make('type')->options([
                    'FIXED' => 'Fixed',
                    'PERCENTAGE' => 'Percentage',
                ])->required(),
                DatePicker::make('start')->default(now())->required()->displayFormat('d-m-Y'),
                DatePicker::make('end')->default(now())->required()->displayFormat('d-m-Y'),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')->sortable()->searchable(),
                TextColumn::make('amount')->sortable()->searchable(),
                TextColumn::make('type')->sortable()->searchable(),
                TextColumn::make('start')->sortable()->date('d-m-Y')->searchable(),
                TextColumn::make('end')->sortable()->date('d-m-Y')->searchable(),
            ])
            ->filters([
              
            ])->defaultSort('id', 'desc')
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListPromoCodes::route('/'),
            'create' => Pages\CreatePromoCode::route('/create'),
            'edit' => Pages\EditPromoCode::route('/{record}/edit'),
        ];
    }
}
