<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\RelationManagers;
use App\Models\Customer;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;
    protected static ?string $navigationGroup = 'Manage Users';
    protected static ?int $navigationSort = -2;
    protected static ?string $navigationIcon = 'phosphor-users-four-light';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('fullname')->label('Name'),
                Forms\Components\TextInput::make('username'),
                Forms\Components\TextInput::make('email'),
                Forms\Components\TextInput::make('mobile_no')->label('Mobile'),
                Forms\Components\TextInput::make('qid')->label('QID'),
                Forms\Components\TextInput::make('owner_type')->label('Profile Type')
                    ->formatStateUsing(function ($state) {
                        return $state == 'I' ? 'Individual' : 'Organization';
                    }),
                Forms\Components\TextInput::make('active')->label('Status')
                    ->formatStateUsing(function ($state) {
                        return $state == '1' ? 'Active' : 'Inactive';
                    }),
                Forms\Components\TextInput::make('created_at')
                    ->formatStateUsing(function ($state) {
                        return Carbon::parse($state)->format('Y-m-d H:i:s');
                    }),
                Forms\Components\TextInput::make('updated_at')->label('Last Updated')
                    ->formatStateUsing(function ($state) {
                        return Carbon::parse($state)->format('Y-m-d H:i:s');
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->searchable()->sortable()->label('ID'),
                Tables\Columns\TextColumn::make('fullname')->searchable()->sortable()->label('Name'),
                Tables\Columns\TextColumn::make('email')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('qid')->searchable()->sortable()->label('QID'),
                Tables\Columns\TextColumn::make('mobile_no')->searchable()->sortable()->label('Mobile'),
                Tables\Columns\ToggleColumn::make('active')->searchable()->sortable()->label('Status'),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }
}
