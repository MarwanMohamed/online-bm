<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuickPayResource\Pages;
use App\Filament\Resources\QuickPayResource\RelationManagers;
use App\Filament\Resources\QuickPayResource\RelationManagers\TransactionsRelationManager;
use App\Models\Quickpay;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
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
        return parent::getEloquentQuery()->where('deleted', 0)->with('user');
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
                    ->label('Policy Group')
                    ->options([
                        'Motor' => 'Motor',
                        'General' => 'General',
                        'Health' => 'Health',
                        'Marine' => 'Marine',
                    ])->required()
                    ->live()
                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                        $set('policy_type', null);
                    }),
                Forms\Components\Select::make('policy_type')
                    ->label('Policy Type')
                    ->options(function (Forms\Get $get) {
                        $category = $get('category');
                        
                        return match ($category) {
                            'Motor' => [
                                'Motor Comprehensive' => 'Motor Comprehensive',
                                'Motor Third Party' => 'Motor Third Party',
                                'Motor Export' => 'Motor Export',
                            ],
                            'Marine' => [
                                'Marine Cargo' => 'Marine Cargo',
                                'Marine Hull & Machinery' => 'Marine Hull & Machinery',
                            ],
                            'Health' => [
                                'MRHI (60+)' => 'MRHI (60+)',
                                'Travel Insurance Inbound' => 'Travel Insurance Inbound',
                                'Travel Insurance Outbound' => 'Travel Insurance Outbound',
                                'Household Workers/ Personal Accident' => 'Household Workers/ Personal Accident',
                                'Individual Medical Insurance' => 'Individual Medical Insurance',
                            ],
                            'General' => [
                                'FAP' => 'FAP',
                                'WCA' => 'WCA',
                                'TPL' => 'TPL',
                                'PAR' => 'PAR',
                                'CAR' => 'CAR',
                                'Professional Indemnity' => 'Professional Indemnity',
                                'Fire & Lightning' => 'Fire & Lightning',
                                'Public Liability' => 'Public Liability',
                                'CAR/TPL' => 'CAR/TPL',
                                'Combined Casualty' => 'Combined Casualty',
                                'CPM' => 'CPM',
                                'PAR & TPL' => 'PAR & TPL',
                                'PAR & PL' => 'PAR & PL',
                                'TPL & PI' => 'TPL & PI',
                                'MB' => 'MB',
                                'FG' => 'FG',
                                'MON' => 'MON',
                                'COMBINED POLICY' => 'COMBINED POLICY',
                                'Professional Indemnity_Reinsurance' => 'Professional Indemnity_Reinsurance',
                                'Public All Risk- Reinsurance' => 'Public All Risk- Reinsurance',
                                'Public Liability Reinsurance' => 'Public Liability Reinsurance',
                                'Business Secure' => 'Business Secure',
                                'PCL (Property All Risk Consequential Loss)' => 'PCL (Property All Risk Consequential Loss)',
                                'All Risk-Machinery (ARM) - Equipment Insurance' => 'All Risk-Machinery (ARM) - Equipment Insurance',
                                'Travel Medical Assistance (TMA)' => 'Travel Medical Assistance (TMA)',
                                'Bankers Blanket Bond (BBB)' => 'Bankers Blanket Bond (BBB)',
                                'Electronic Equipment Insurance (EEI)' => 'Electronic Equipment Insurance (EEI)',
                                'Erection All Risk (EAR)' => 'Erection All Risk (EAR)',
                            ],
                            default => [],
                        };
                    })
                    ->required(),
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

                TextColumn::make('category')->label('Policy Group')->searchable()->sortable()
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'General' => 'gray',
                        'Health' => 'blue',
                        'Motor' => 'orange',
                        'Marine' => 'green',
                        default => 'gray',
                    }),
                TextColumn::make('policy_type')->label('Policy Type')->searchable()->sortable()
                    ->badge()
                    ->color('info'),
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
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from')
                            ->label('Created From'),
                        DatePicker::make('created_until')
                            ->label('Created Until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
                SelectFilter::make('status')
                    ->options([
                        '1' => 'Unpaid',
                        '0' => 'Paid',
                    ])
                    ->placeholder('Select Status'),
                SelectFilter::make('category')
                    ->label('Policy Group')
                    ->options([
                        'General' => 'General',
                        'Health' => 'Health',
                        'Motor' => 'Motor',
                        'Marine' => 'Marine',
                    ])
                    ->placeholder('Select Policy Group')
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->defaultSort('created_at', 'desc')
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
