<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuickpayReportsResource\Pages;
use App\Filament\Resources\QuickpayReportsResource\RelationManagers;
use App\Models\Quickpay;
use App\Models\QuickpayReports;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class QuickpayReportsResource extends Resource
{
    protected static ?string $model = Quickpay::class;
    protected static ?string $label = 'Quickpay Reports';
    protected static ?string $navigationGroup = 'Reports';
    protected static ?int $navigationSort = 3;

    protected static ?string $navigationIcon = 'phosphor-table';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('deleted', 0)
            ->with('user');
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

//            $this->dbp.name, qp.amount, qp.status,  ad.name as ad_name');

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('ref_no')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('amount')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('user.name')->sortable()->searchable(),
                TextColumn::make('status')->label('Status')
                    ->badge()
                    ->getStateUsing(fn($record) => $record->status == 0 ? 'Paid' : 'Unpaid')
                    ->color(fn(string $state): string => match ($state) {
                        'Paid' => 'success',
                        'Unpaid' => 'danger',
                    })
                    ->searchable()->sortable(),
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
            'index' => Pages\ListQuickpayReports::route('/'),
            'create' => Pages\CreateQuickpayReports::route('/create'),
            'edit' => Pages\EditQuickpayReports::route('/{record}/edit'),
        ];
    }
}
