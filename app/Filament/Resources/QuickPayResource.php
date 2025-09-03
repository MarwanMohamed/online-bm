<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuickPayResource\Pages;
use App\Filament\Resources\QuickPayResource\RelationManagers;
use App\Filament\Resources\QuickPayResource\RelationManagers\TransactionsRelationManager;
use App\Models\Quickpay;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class QuickPayResource extends Resource
{
    protected static ?string $model = QuickPay::class;
    protected static ?int $navigationSort = 4;
    protected static ?string $label = 'Quick Pay';
    protected static ?string $navigationIcon = 'phosphor-currency-dollar';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('deleted', 0)->with('user')
            ->orderBy('created_at', 'desc');
    }

//    public static function getNavigationBadge(): ?string
//    {
//        return static::getModel()::where('deleted', 0)->count();
//    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('category')
                    ->label('Category')
                    ->options([
                        'general' => 'General',
                        'medical' => 'Medical',
                        'mvhi' => 'MVHI',
                        'life' => 'Life',
                        'motor' => 'Motor',
                    ])->required()
                    ->live()
                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                        $refNoMap = [
                            'general' => 'genr',
                            'medical' => 'med',
                            'mvhi' => 'mhi',
                            'life' => 'lfe',
                            'motor' => 'mot',
                        ];
                        
                        if (isset($refNoMap[$state])) {
                            $value = $refNoMap[$state];
                            $set('ref_no', $value);
                            $set('description', $value);
                        }
                    })
                    ->dehydrated(false)
                    ->hiddenOn('edit'),
                Forms\Components\TextInput::make('ref_no')
                    ->label('Reference Number')
                    ->required()
                    ->unique(QuickPay::class, 'ref_no', ignoreRecord: true)
                    ->disabled(fn($record) => $record !== null)
                    ->readOnly(fn($record) => $record !== null),
                Forms\Components\TextInput::make('name')->required(),
                Forms\Components\TextInput::make('amount')->numeric()->step('any')->required(),
                Forms\Components\TextInput::make('status')->readOnly()->hiddenOn('create')->required()
                    ->formatStateUsing(fn($record) => isset($record->status) && $record->status == 0 ? 'Paid' : 'Unpaid'),
                Forms\Components\TextInput::make('email')->required(),
                Forms\Components\TextInput::make('contact')->required()->maxValue(8),
                Forms\Components\Textarea::make('description')->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('Sl')->searchable()->sortable(),
                TextColumn::make('created_at')->label('Date')->searchable()->sortable()
                    ->getStateUsing(fn($record) => date('d/m/Y h:i A', strtotime($record->created_at))),

                TextColumn::make('ref_no')->label('Reference #')->searchable()->sortable(),
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('amount')->searchable()->sortable(),
                TextColumn::make('status')->label('Status')
                    ->badge()
                    ->getStateUsing(fn($record) => $record->status == 0 ? 'Paid' : 'Unpaid')
                    ->color(fn(string $state): string => match ($state) {
                        'Paid' => 'success',
                        'Unpaid' => 'danger',
                    })
                    ->searchable()->sortable(),
                TextColumn::make('user.name')->label('Agent')->searchable()->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        '1' => 'Unpaid',
                        '0' => 'Paid',
                    ])
                    ->placeholder('Select Status')
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
            TransactionsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuickPays::route('/'),
            'create' => Pages\CreateQuickPay::route('/create'),
            'edit' => Pages\EditQuickPay::route('/{record}/edit'),
        ];
    }
}
