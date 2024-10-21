<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AgentResource\Pages;
use App\Filament\Resources\AgentResource\RelationManagers;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AgentResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationGroup = 'Manage Users';
    protected static ?int $navigationSort = -1;
    protected static ?string $navigationIcon = 'phosphor-user-light';
    protected static ?string $label = 'Agent';
    protected static ?string $pluralLabel = 'Agents';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name'),
                TextInput::make('phone'),
                TextInput::make('email'),
                TextInput::make('username'),
                TextInput::make('password')->password(),
                TextInput::make('passwordConformation')->visibleOn('create')->password()->same('password')->dehydrated(),
                Select::make('role')->options(Role::get()->pluck('title', 'id'))
                ->searchable()
                ->preload()
                ->required(),
                Toggle::make('status')->inline(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->searchable()->sortable()->label('ID'),
                Tables\Columns\TextColumn::make('image'),
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('email')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('role')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('last_login')
                    ->getStateUsing(function ($record) {
                        return Carbon::parse($record->last_login)->format('d F, Y');
                    })->searchable()->sortable(),
                Tables\Columns\ToggleColumn::make('status')->searchable()->sortable(),

            ])
            ->filters([
                //
            ])
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
            'index' => Pages\ListAgents::route('/'),
            'create' => Pages\CreateAgent::route('/create'),
            'edit' => Pages\EditAgent::route('/{record}/edit'),
        ];
    }
}
