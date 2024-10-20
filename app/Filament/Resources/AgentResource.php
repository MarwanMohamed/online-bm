<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AgentResource\Pages;
use App\Filament\Resources\AgentResource\RelationManagers;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms;
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
//                Forms\Components\TextInput::make('fullname')->label('Name'),
//                Forms\Components\TextInput::make('username'),
//                Forms\Components\TextInput::make('email'),
//                Forms\Components\TextInput::make('mobile_no')->label('Mobile'),
//                Forms\Components\TextInput::make('qid')->label('QID'),
//                Forms\Components\TextInput::make('owner_type')->label('Profile Type')
//                    ->formatStateUsing(function ($state) {
//                        return $state == 'I' ? 'Individual' : 'Organization';
//                    }),
//                Forms\Components\TextInput::make('active')->label('Status')
//                    ->formatStateUsing(function ($state) {
//                        return $state == '1' ? 'Active' : 'Inactive';
//                    }),
//                Forms\Components\TextInput::make('created_at')
//                    ->formatStateUsing(function ($state) {
//                        return Carbon::parse($state)->format('Y-m-d H:i:s');
//                    }),
//                Forms\Components\TextInput::make('updated_at')->label('Last Updated')
//                    ->formatStateUsing(function ($state) {
//                        return Carbon::parse($state)->format('Y-m-d H:i:s');
//                    }),
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
