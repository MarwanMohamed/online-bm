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
                        'Personal Accident' => 'Personal Accident',
                    ])->required()
                    ->live()
                    ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                        $set('policy_type', null);
                        
                        $currentDescription = $get('description') ?? '';
                        
                        $existingContent = '';
                        if (preg_match('/^[^\n]*\n(.*)$/s', $currentDescription, $matches)) {
                            $existingContent = $matches[1];
                        } elseif (!preg_match('/^[^\n]*\n/', $currentDescription)) {
                            $existingContent = $currentDescription;
                        }
                        
                        $set('description', $existingContent);
                    }),
                Forms\Components\Select::make('policy_type')
                    ->label('Policy Type')
                    ->options(function (Forms\Get $get) {
                        return static::getPolicyTypeOptions($get('category'));
                    })
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                        $category = $get('category');
                        $policyType = $state;
                        $currentDescription = $get('description') ?? '';
                        
                        $existingContent = '';
                        if (preg_match('/^[^\n]*\n(.*)$/s', $currentDescription, $matches)) {
                            $existingContent = $matches[1];
                        } elseif (!preg_match('/^[^\n]*\n/', $currentDescription)) {
                            $existingContent = $currentDescription;
                        }
                        
                        $newPrefix = ($category && $policyType) ? "{$category}/{$policyType}\n" : '';
                        
                        $set('description', $newPrefix . $existingContent);
                    }),
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
                Forms\Components\TextInput::make('email')->required()->email(),
                Forms\Components\TextInput::make('contact')->required()->maxValue(8),
                Forms\Components\Textarea::make('description')
                    ->required()
                    ->live()
                    ->formatStateUsing(function ($state, $record, Forms\Get $get) {
                        $category = $get('category');
                        $policyType = $get('policy_type');
                        
                        if ($category && $policyType) {
                            $expectedPrefix = "{$category}/{$policyType}\n";
                            if (strpos($state, $expectedPrefix) === 0) {
                                return $state;
                            }
                            return $expectedPrefix . $state;
                        }
                        
                        return $state;
                    })
                    ->dehydrateStateUsing(function ($state, Forms\Get $get) {
                        // Save the full description including the prefix
                        return $state;
                    })
                    ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                        $category = $get('category');
                        $policyType = $get('policy_type');
                        
                        if ($category && $policyType) {
                            $prefix = "{$category}/{$policyType}\n";
                            if (strpos($state, $prefix) !== 0) {
                                $set('description', $prefix . $state);
                            }
                        }
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')->label('Date')->searchable()->sortable()
                    ->getStateUsing(fn($record) => date('d/m/Y h:i A', strtotime($record->created_at))),

                TextColumn::make('category')->label('Policy Group')->searchable()->sortable()
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'General' => 'gray',
                        'Health' => 'success',
                        'Motor' => 'warning',
                        'Marine' => 'info',
                        'Personal Accident' => 'primary',
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
                        'Personal Accident' => 'Personal Accident',
                    ])
                    ->placeholder('Select Policy Group')
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->icon('heroicon-o-eye')
                    ->label(''),
                Tables\Actions\EditAction::make()
                    ->icon('heroicon-o-pencil')
                    ->label(''),
                Tables\Actions\DeleteAction::make()
                    ->icon('heroicon-o-trash')
                    ->label(''),
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

    /**
     * Get policy type options based on category
     */
    private static function getPolicyTypeOptions(?string $category): array
    {
        $policyTypes = [
            'Motor' => [
                'Comprehensive',
                'Export',
                'Third Party',
            ],
            'Marine' => [
                'Cargo',
                'Hull & Machinery',
            ],
            'Health' => [
                'Individual Medical Insurance',
                'MRHI (60+)',
                'Travel Insurance Inbound',
                'Travel Insurance Outbound',
            ],
            'General' => [
                'Bankers Blanket Bond (BBB)',
                'Business Secure',
                'Combined Casualty',
                'COMBINED POLICY',
                'Contractor All Risks (CAR)',
                'Contractors Plant & Machinery (CPM)',
                'Cyber Liability',
                'Electronic Equipment Insurance (EEI)',
                'Erection All Risk (EAR)',
                'Event Insurance',
                'Fidelity Guarantee (FG)',
                'Fire & Lightning',
                'Fire and Allied Perils (FAP)',
                'Group Life + Personal Accident (GLPA)',
                'Group Personal Accident',
                'Home Insurance',
                'Machinery Breakdown (MB)',
                'Medical Malpractice',
                'Money (MON)',
                'Professional Indemnity (PI)',
                'Property All Risks (PAR)',
                'SME - Small Medium Enterprise',
                'Third Party Liability (TPL)',
                'Workmen\'s Compensation Assurance (WCA)',
            ],
            'Personal Accident' => [
                'Household Workers',
            ],
        ];

        if (!$category || !isset($policyTypes[$category])) {
            return [];
        }

        // Convert array to key-value pairs for select options
        return array_combine($policyTypes[$category], $policyTypes[$category]);
    }
}
