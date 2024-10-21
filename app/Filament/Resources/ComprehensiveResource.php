<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ComprehensiveResource\Pages;
use App\Models\Area;
use App\Models\Company;
use App\Models\Insurance;
use App\Models\Thirdparty;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Tables;
use Filament\Tables\Table;
use IbrahimBougaoua\RadioButtonImage\Actions\RadioButtonImage;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Resource;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class ComprehensiveResource extends Resource
{
    protected static ?string $model = Insurance::class;
    protected static ?string $label = 'Comprehensive';
    protected static ?int $navigationSort = 2;

    protected static ?string $navigationIcon = 'phosphor-article';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('ins_type', 'Comprehensive')
            ->where('deleted', 0)->with('user')
            ->orderBy('created_at', 'desc');
    }

//    public static function getNavigationBadge(): ?string
//    {
//        return static::getModel()::where('ins_type', 'Comprehensive')->where('deleted', 0)->count();
//    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('Owner Details')->icon('phosphor-user-light')
                        ->schema([
                            Radio::make('owner_type')->options([
                                'I' => 'Individual', 'O' => 'Organisation',
                            ])->inline(),
                            TextInput::make('name')->label('Full Name'),
                            TextInput::make('qid')->label('EID/QID'),
                            TextInput::make('mobile')->label('Mobile Number'),
                            TextInput::make('phone')->label('Phone Number'),
                            TextInput::make('email')->label('Email'),
                            Select::make('area')->searchable()->options(Area::pluck('area', 'id')),
                        ]),
                    Wizard\Step::make('Vehicle Details')->icon('phosphor-car-light')
                        ->schema([
                            TextInput::make('vhl_make')->label('Make'),
                            TextInput::make('vhl_class')->label('Model'),
                            TextInput::make('vhl_chassis')->label('Chassis #'),
                            TextInput::make('vhl_engine')->label('Engine #'),
                            TextInput::make('vhl_reg_no')->label('Plate #'),
                            TextInput::make('vhl_color')->label('Color'),
                            Select::make('vhl_year')->options(array_combine(range(now()->year, now()->year - 50), range(now()->year, now()->year - 50))),

                            DatePicker::make('start_date')->native(false)
                                ->reactive()
                                ->afterStateUpdated(function ($state, $set) {
                                    $startDate = Carbon::parse($state);
                                    $endDate = $startDate->addYear()->subDay();
                                    $set('end_date', $endDate);
                                }),
                            DatePicker::make('end_date')->native(false)->disabled(),
                            TextInput::make('vhl_value')->label("Insured's Declared Value:")->disabled()->readOnly(),
                        ]),
                    Wizard\Step::make('Policy Details')->icon('phosphor-invoice')
                        ->schema([
                            RadioButtonImage::make('com_id')
                                ->label('Company')
                                ->options(
                                    Company::orderBy('priority')->where('active', 1)->get()->pluck('logo', 'id')->toArray()
                                ),

                            Select::make('opt_1')->label('Type of Vehicle ')->searchable()
                                ->options(Thirdparty::where('parent_id', 0)->pluck('value', 'id'))
                                ->live(),

                            Select::make('opt_2')->hidden(fn(Get $get): bool => !filled($get('opt_1')))
                                ->options(fn(Get $get) => Thirdparty::where('parent_id', $get('opt_1'))->pluck('value', 'id'))
                                ->label('Select vehicle class')
                                ->afterStateUpdated(function ($state, $set) {
                                    if ($state) {
                                        $max_pass = Thirdparty::where('id', $state)->first()->max_pass;
                                        if ($max_pass != 0) {
                                            $set('passengers', $max_pass);
                                        }
                                    }
                                })->live(),

                            Select::make('opt_3')->hidden(fn(Get $get): bool => !filled($get('opt_2')) || Thirdparty::where('parent_id', $get('opt_2'))->count() == 0)
                                ->options(fn(Get $get) => Thirdparty::where('parent_id', $get('opt_2'))->pluck('value', 'id'))
                                ->label('Select no. of cylinders')
                                ->afterStateUpdated(function ($state, Set $set) {
                                    if ($state) {
                                        $max_pass = Thirdparty::where('id', $state)->first()->max_pass;
                                        if ($max_pass != 0) {
                                            $set('passengers', $max_pass);
                                        }
                                    }
                                })->live(),

                            Select::make('opt_4')->hidden(fn(Get $get): bool => !filled($get('opt_3')) || Thirdparty::where('parent_id', $get('opt_3'))->count() == 0)
                                ->options(fn(Get $get) => Thirdparty::where('parent_id', $get('opt_3'))->pluck('value', 'id'))
                                ->label('Select Details')
                                ->afterStateUpdated(function ($state, Set $set) {
                                    if ($state) {
                                        $max_pass = Thirdparty::where('id', $state)->first()->max_pass;
                                        if ($max_pass != 0) {
                                            $set('passengers', $max_pass);
                                        }
                                    }
                                })->live(),

                            Select::make('passengers')->hidden(fn(Get $get): bool => $get('passengers') == null)
                                ->options(function (Get $get) {
                                    $options = [];
                                    for ($i = 1; $i <= $get('passengers'); $i++) {
                                        $options[$i] = $i;
                                    }
                                    return $options;
                                })->label('Select no. of passengers'),
                        ]),
                    Wizard\Step::make('Images')->icon('phosphor-images')
                        ->schema([
                            SpatieMediaLibraryFileUpload::make('qid_img')->maxSize(3000)->label(__('QID'))->collection('image')->columnSpan(2),
                            SpatieMediaLibraryFileUpload::make('isb_img')->maxSize(3000)->label(__('ISTIMARA Back'))->collection('image')->columnSpan(2),
                            SpatieMediaLibraryFileUpload::make('isf_img')->maxSize(3000)->label(__('ISTIMARA Front'))->collection('image')->columnSpan(2),
                            SpatieMediaLibraryFileUpload::make('vhl_fnt')->maxSize(3000)->label(__('Front'))->collection('image')->columnSpan(2),
                            SpatieMediaLibraryFileUpload::make('vhl_bck')->maxSize(3000)->label(__('Back'))->collection('image')->columnSpan(2),
                            SpatieMediaLibraryFileUpload::make('vhl_lft')->maxSize(3000)->label(__('Left'))->collection('image')->columnSpan(2),
                            SpatieMediaLibraryFileUpload::make('vhl_rgt')->maxSize(3000)->label(__('Right'))->collection('image')->columnSpan(2),
                        ]),
                    Wizard\Step::make('Premium Details')
                        ->hiddenOn('create')
                        ->schema([
                            TextInput::make('base_amount')->label('Base Price')->disabled()->readOnly(),
                            TextInput::make('pass_amount')->label('Passenger Price')->disabled()->readOnly(),
                            TextInput::make('opt_amount')->label('Optional Price')->disabled()->readOnly(),
                            TextInput::make('discount')->label('Discount')->disabled()->readOnly(),
                            TextInput::make('total_amount')->label('Total')->disabled()->readOnly(),
                        ]),
                    Wizard\Step::make('Status Details')
                        ->hiddenOn('create')
                        ->schema([
                            Radio::make('active')->label('Admin Status')->options([
                                '1' => 'Active',
                                '0' => 'Inactive',
                            ])->inline(),
                            Radio::make('status')->label('Policy Status')->options([
                                '4' => 'In Progress',
                                '2' => 'Paid',
                                '6' => 'Verification',
                                '7' => 'Lost'
                            ])->inline()->reactive(),

                            TextInput::make('vendor_policy_no')->label('Vendor Policy No.')
                                ->hidden(fn($get) => $get('status') == 7),
                            TextInput::make('description')->label('Reason')
                                ->visible(fn($get) => $get('status') == 7),
                        ]),
                ])->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('Sl')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('created_at')->label('Date')->searchable()->sortable()
                    ->getStateUsing(fn($record) => date('d/m/Y h:i A', strtotime($record->created_at))),

                Tables\Columns\TextColumn::make('policy_id')->label('Reference #')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('name')->label('Name')->searchable()->sortable(),
                Tables\Columns\IconColumn::make('active')->label('Status')->searchable()->sortable()
                    ->icon(fn(string $state): string => match ($state) {
                        '1' => 'heroicon-o-check',
                        '0' => 'heroicon-o-x-mark',
                    })
                    ->color(fn(string $state): string => match ($state) {
                        '0' => 'danger',
                        '1' => 'success',
                    }),
                Tables\Columns\TextColumn::make('user.name')->label('Agent')->searchable()->sortable(),
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
            'index' => Pages\ListComprehensives::route('/'),
            'create' => Pages\CreateComprehensive::route('/create'),
            'edit' => Pages\EditComprehensive::route('/{record}/edit'),
        ];
    }
}
