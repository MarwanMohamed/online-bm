<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityLogsResource\Pages;
use App\Filament\Resources\ActivityLogsResource\RelationManagers;
use App\Models\ActivityLog;
use App\Models\ActivityLogs;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ActivityLogsResource extends Resource
{
    protected static ?string $model = ActivityLog::class;
    protected static ?string $navigationGroup = 'Manage Users';
    protected static ?int $navigationSort = 4;
    protected static ?string $navigationIcon = 'phosphor-notepad';

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
                Tables\Columns\TextColumn::make('id')->label('ID')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('user.name')->searchable()->sortable()
                ->url(fn ($record) => 'agents/'.$record->user_id.'/edit' ),
                Tables\Columns\TextColumn::make('ip_address')->searchable()->sortable()->label('IP Address'),
                Tables\Columns\TextColumn::make('title')->label('Message')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('created_at')->label('Date & Time')->searchable()->sortable(),
            ])
            ->filters([

                SelectFilter::make('user_id')
                    ->label('Filter by User')
                    ->options(User::all()->pluck('name', 'id')->map(function ($name, $id) {
                        return "{$name} # {$id}"; // Concatenate ID to the name
                    })->toArray()),
            ])
            ->actions([
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
            'index' => Pages\ListActivityLogs::route('/'),
            'create' => Pages\CreateActivityLogs::route('/create'),
            'edit' => Pages\EditActivityLogs::route('/{record}/edit'),
        ];
    }
}
