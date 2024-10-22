<?php

namespace App\Filament\Resources;

use Althinect\FilamentSpatieRolesPermissions\Resources\RoleResource as DefaultRoleResource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/*
|--------------------------------------------------------------------------
| RoleResource Class
|--------------------------------------------------------------------------
|
| Represents a role resource and extends the DefaultRoleResource class.
| Provides definitions for forms, tables, and resource-specific pages.
|
*/

class RoleResource extends DefaultRoleResource

{
    protected static ?string $navigationGroup = 'Manage Users';
    protected static ?int $navigationSort = -1;

    /*
    |--------------------------------------------------------------------------
    | Form Definition
    |--------------------------------------------------------------------------
    |
    | Defines the form schema for creating and editing roles.
    |
    | @param Form $form The form instance.
    | @return Form
    |
    */
    public static function form(Form $form): Form
    {
        return $form->schema([

            TextInput::make('name')->required()
                ->columnSpanFull(),

            Select::make('permissions')
                ->getOptionLabelFromRecordUsing(fn(Model $record) => $record->name)
                ->preload()
                ->columnSpanFull()
                ->relationship(
                    name: 'permissions',
                    modifyQueryUsing: fn(Builder $query) => $query->orderBy('name'),
                )
                ->multiple()
                ->searchable()
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Table Definition
    |--------------------------------------------------------------------------
    |
    | Defines the table schema for displaying roles.
    |
    | @param Table $table The table instance.
    | @return Table
    |
    */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('name'),
            ])
            ->filters([

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Resource Pages
    |--------------------------------------------------------------------------
    |
    | Defines the resource-specific pages and their routes.
    |
    | @return array
    |
    */
    public static function getPages(): array
    {
        return [
            'index' => RoleResource\Pages\ListRoles::route('/'),
            'create' => RoleResource\Pages\CreateRole::route('/create'),
            'edit' => RoleResource\Pages\EditRole::route('/{record}/edit'),
        ];
    }


}
