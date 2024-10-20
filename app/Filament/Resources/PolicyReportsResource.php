<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PolicyReportsResource\Pages;
use App\Filament\Resources\PolicyReportsResource\RelationManagers;
use App\Models\Insurance;
use App\Models\PolicyReports;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PolicyReportsResource extends Resource
{
    protected static ?string $model = Insurance::class;
    protected static ?string $label = 'Policy Reports';
    protected static ?string $navigationGroup = 'Reports';
    protected static ?int $navigationSort = 3;

    protected static ?string $navigationIcon = 'phosphor-table';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('deleted', 0)
            ->with('getStatus', 'company', 'user', 'transaction');
    }

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
                Tables\Columns\TextColumn::make('created_at')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('policy_id')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('ins_type')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('company.name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('transaction.amount')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('user.name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('getStatus.status')->label('Policy Status')
                    ->badge()->searchable()->sortable()
                    ->color(fn(string $state): string => match ($state) {
                        'To Renew', 'Verification', 'Expired', 'Lost' => 'danger',
                        'Paid' => 'info',
                        'Issued' => 'success',
                        'In Progress' => 'warning',
                        'Refunded' => 'gray',
                    }),
                Tables\Columns\TextColumn::make('transaction.status')->sortable()->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
//                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListPolicyReports::route('/'),
            'create' => Pages\CreatePolicyReports::route('/create'),
            'edit' => Pages\EditPolicyReports::route('/{record}/edit'),
        ];
    }
}
