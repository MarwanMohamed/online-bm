<?php

namespace App\Filament\Resources;

use Althinect\FilamentSpatieRolesPermissions\Resources\RoleResource as DefaultRoleResource;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables;

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
    protected static ?int $navigationSort = 3;

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
//            AppFormsComponents\RoleName::make()
//                ->columnSpanFull(),
//            AppFormsComponents\PermissionSelect::make()
//                ->columnSpanFull(),
//            AppFormsComponents\PermissionSelectAll::make()
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
                AppTablesComponents\IDColumn::make(),
                AppTablesComponents\RoleNameColumn::make(),
                AppTablesComponents\PermissionsCountColumn::make(),
                AppTablesComponents\GuardNameColumn::make(),
                AppTablesComponents\CreatedAtColumn::make(),
                AppTablesComponents\UpdatedAtColumn::make(),
            ])
            ->filters([

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
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

    /*
    |--------------------------------------------------------------------------
    | getEloquentQuery Method
    |--------------------------------------------------------------------------
    |
    | Override the getEloquentQuery method to customize the query.
    | This method returns the parent query with an additional condition
    | to filter records where 'tema_id' is NULL.
    |
    | @return \Illuminate\Database\Eloquent\QueryBuilder
    |
    */
    public static function getEloquentQuery(): Builder
    {
        // Get the parent query and add a condition
        return parent::getEloquentQuery()->whereNull('team_id');
    }
}
