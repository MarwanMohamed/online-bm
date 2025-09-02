<?php

namespace App\Filament\Resources;

use Althinect\FilamentSpatieRolesPermissions\Resources\PermissionResource as DefaultPermissionResource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/*
|--------------------------------------------------------------------------
| PermissionResource Class
|--------------------------------------------------------------------------
|
| Represents a permission resource and extends the DefaultPermissionResource class.
| Provides definitions for forms, tables, and resource-specific pages.
|
*/

class PermissionResource extends DefaultPermissionResource

{
    protected static ?string $navigationGroup = 'Manage Users';
    protected static ?int $navigationSort = 2;

    /*
    |--------------------------------------------------------------------------
    | Form Definition
    |--------------------------------------------------------------------------
    |
    | Defines the form schema for creating and editing permissions.
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

            Select::make('roles')
                ->getOptionLabelFromRecordUsing(fn(Model $record) => $record->name)
                ->preload()
                ->columnSpanFull()
                ->relationship(
                    name: 'roles',
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
    | Defines the table schema for displaying permissions.
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
                Tables\Actions\ViewAction::make(),
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ])
            ->emptyStateActions([
                // Tables\Actions\CreateAction::make(),
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
            'index' => PermissionResource\Pages\ListPermissions::route('/'),
            'view' => PermissionResource\Pages\ViewPermission::route('/{record}'),
            // 'create' => PermissionResource\Pages\CreatePermission::route('/create'),
            // 'edit' => PermissionResource\Pages\EditPermission::route('/{record}/edit'),
        ];
    }


}
