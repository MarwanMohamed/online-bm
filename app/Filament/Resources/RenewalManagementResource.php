<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RenewalManagementResource\Pages;
use App\Models\Area;
use App\Models\Company;
use App\Models\Insurance;
use App\Models\Thirdparty;
use App\Models\Vehicle;
use App\Models\VehicleBodyType;
use App\Models\VehicleColor;
use App\Models\VehicleModel;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use IbrahimBougaoua\RadioButtonImage\Actions\RadioButtonImage;
use Illuminate\Database\Eloquent\Builder;
use TessPayments\Checkout\CheckoutService;

class RenewalManagementResource extends Resource
{
    protected static ?string $model = Insurance::class;
    protected static ?string $label = 'Renewal Managements';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationIcon = 'heroicon-o-document-currency-dollar';

    public static function canViewAny(): bool
    {
        return \Auth::user()->hasPermissionTo('Renewal Management');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('deleted', 0)
            ->where('pb_no', "renewal")
            ->with('user');
            //->orderBy('id', 'desc');
    }

//    public static function getNavigationBadge(): ?string
//    {
//        return static::getModel()::where('deleted', 0)->where('pb_no', "renewal")->count();
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
                            ])->inline()->required(),
                            TextInput::make('name')->label('Full Name')->required(),
                            TextInput::make('qid')->label('EID/QID')->required(),
                            TextInput::make('mobile')->label('Mobile Number')->required(),
                            TextInput::make('phone')->label('Phone Number')->required(),
                            TextInput::make('email')->label('Email')->required(),
                            Select::make('area')->searchable()->options(Area::pluck('area', 'id'))->required(),
                        ]),
                    Wizard\Step::make('Vehicle Details')->icon('phosphor-car-light')
                        ->schema([
                            Select::make('vhl_make')->label('Make')
                                ->options(Vehicle::where('active', 1)->pluck('name', 'id'))
                                ->searchable()
                                ->required()
                                ->live(),
                            Select::make('vhl_class')->label('Model')
                                ->options(function (Get $get) {
                                    $makeId = $get('vhl_make');
                                    if ($makeId) {
                                        return VehicleModel::where('make_id', $makeId)
                                            ->where('active', 1)
                                            ->pluck('model_name', 'id');
                                    }
                                    return [];
                                })
                                ->searchable()
                                ->required()
                                ->hidden(fn(Get $get): bool => !filled($get('vhl_make'))),
                            Select::make('vhl_body_type')->label('Body Type')
                                ->options(VehicleBodyType::where('active', 1)->pluck('name', 'id'))
                                ->searchable()
                                ->required(),
                            TextInput::make('vhl_chassis')->label('Chassis #')->required(),
                            TextInput::make('vhl_engine')->label('Engine #')->required(),
                            TextInput::make('vhl_reg_no')->label('Plate #')->required(),
                            Select::make('vhl_color')->label('Color')
                                ->options(VehicleColor::pluck('name', 'id'))
                                ->searchable()
                                ->required(),
                            Select::make('vhl_year')->options(array_combine(range(now()->year, now()->year - 50), range(now()->year, now()->year - 50))),

                            DatePicker::make('start_date')->native(false)->required()
                                ->reactive()
                                ->minDate(today())
                                ->default(today())
                                ->afterStateUpdated(function ($state, $set) {
                                    $startDate = Carbon::parse($state);
                                    $endDate = $startDate->addYear()->subDay();
                                    $set('end_date', $endDate);
                                }),
                            DatePicker::make('end_date')->default(today()->addYear()->subDay())->native(false)->readOnly()
                        ]),
                    Wizard\Step::make('Policy Details')->icon('phosphor-invoice')
                        ->schema([
                            TextInput::make('policy_id')->label('Reference Number')->hiddenOn(['create', 'edit']),
                            TextInput::make('ins_type')->label('Type of Insurance')->hiddenOn(['create', 'edit']),
                            RadioButtonImage::make('com_id')->required()
                                ->label('Company')
                                ->options(
                                    Company::orderBy('priority')->where('active', 1)->get()->pluck('logo', 'id')->toArray()
                                ),

                            Select::make('opt_1')->label('Type of Vehicle ')->searchable()->required()
                                ->options(Thirdparty::where('parent_id', 0)->where('deleted', 0)->pluck('value', 'id'))
                                ->live()
                                ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                    $set('opt_2', null);
                                    $set('opt_3', null);
                                    $set('opt_4', null);
                                    $set('passengers', null);
                                    static::updatePricing($get, $set);
                                }),

                            Select::make('opt_2')->required()->hidden(fn(Get $get): bool => !filled($get('opt_1')))
                                ->options(fn(Get $get) => Thirdparty::where('parent_id', $get('opt_1'))->pluck('value', 'id'))
                                ->label('Select vehicle class')
                                ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                    if ($state) {
                                        $max_pass = Thirdparty::where('id', $state)->first()->max_pass;
                                        if ($max_pass != 0) {
                                            $set('passengers', $max_pass);
                                        }
                                    }
                                    $set('opt_3', null);
                                    $set('opt_4', null);
                                    static::updatePricing($get, $set);
                                })->live(),

                            Select::make('opt_3')->hidden(fn(Get $get): bool => !filled($get('opt_2')) || Thirdparty::where('parent_id', $get('opt_2'))->count() == 0)
                                ->options(fn(Get $get) => Thirdparty::where('parent_id', $get('opt_2'))->pluck('value', 'id'))
                                ->label('Select no. of cylinders')
                                ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                    if ($state) {
                                        $max_pass = Thirdparty::where('id', $state)->first()->max_pass;
                                        if ($max_pass != 0) {
                                            $set('passengers', $max_pass);
                                        }
                                    }
                                    $set('opt_4', null);
                                    static::updatePricing($get, $set);
                                })->live(),

                            Select::make('opt_4')->hidden(fn(Get $get): bool => !filled($get('opt_3')) || Thirdparty::where('parent_id', $get('opt_3'))->count() == 0)
                                ->options(fn(Get $get) => Thirdparty::where('parent_id', $get('opt_3'))->pluck('value', 'id'))
                                ->label('Select Details')
                                ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                    if ($state) {
                                        $max_pass = Thirdparty::where('id', $state)->first()->max_pass;
                                        if ($max_pass != 0) {
                                            $set('passengers', $max_pass);
                                        }
                                    }
                                    static::updatePricing($get, $set);
                                })->live(),

                            Select::make('passengers')->hidden(fn(Get $get): bool => $get('passengers') == null)
                                ->options(function (Get $get) {
                                    $options = [];
                                    for ($i = 1; $i <= $get('passengers'); $i++) {
                                        $options[$i] = $i;
                                    }
                                    return $options;
                                })->label('Select no. of passengers')
                                ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                    static::updatePricing($get, $set);
                                })->live(),
                        ]),
                    Wizard\Step::make('Images')->icon('phosphor-images')
                        ->schema([
                            SpatieMediaLibraryFileUpload::make('image_qid_img')->maxSize(3000)->label(__('QID'))
                                ->required()->collection('image_qid_img')->columnSpan(2),
                            SpatieMediaLibraryFileUpload::make('image_isb_img')->maxSize(3000)->label(__('ISTIMARA Back'))
                                ->required()->collection('image_isb_img')->columnSpan(2),
                            SpatieMediaLibraryFileUpload::make('image_isf_img')->maxSize(3000)->label(__('ISTIMARA Front'))
                                ->required()->collection('image_isf_img')->columnSpan(2),
                            SpatieMediaLibraryFileUpload::make('image_vhl_fnt')->maxSize(3000)->label(__('Front'))
                                ->required()->collection('image_vhl_fnt')->columnSpan(2),
                            SpatieMediaLibraryFileUpload::make('image_vhl_bck')->maxSize(3000)->label(__('Back'))
                                ->required()->collection('image_vhl_bck')->columnSpan(2),
                            SpatieMediaLibraryFileUpload::make('image_vhl_lft')->maxSize(3000)->label(__('Left'))
                                ->required()->collection('image_vhl_lft')->columnSpan(2),
                            SpatieMediaLibraryFileUpload::make('image_vhl_rgt')->maxSize(3000)->label(__('Right'))
                                ->required()->collection('image_vhl_rgt')->columnSpan(2),
                        ]),
                    Wizard\Step::make('Premium Details')
                        ->schema([
                            TextInput::make('base_amount')->label('Base Price')
                                ->disabled()
                                ->readOnly()
                                ->dehydrated(false),
                            TextInput::make('pass_amount')->label('Passenger Price')
                                ->disabled()
                                ->readOnly()
                                ->dehydrated(false),
                            TextInput::make('opt_amount')->label('Optional Price')
                                ->disabled()
                                ->readOnly()
                                ->dehydrated(false),
                            TextInput::make('discount')->label('Discount')
                                ->disabled()
                                ->readOnly()
                                ->dehydrated(false),
                            TextInput::make('total_amount')->label('Total')
                                ->disabled()
                                ->readOnly()
                                ->dehydrated(false),
                        ]),
                    Wizard\Step::make('Status Details')
                        ->schema([
                            Radio::make('active')->label('Admin Status')->options([
                                '1' => 'Active',
                                '0' => 'Inactive',
                            ])->inline()->default(1),
                            Radio::make('status')->label('Policy Status')->options([
                                '4' => 'In Progress',
                                '2' => 'Paid',
                                '6' => 'Verification',
                                '7' => 'Lost'
                            ])->inline()->reactive()->default(1),

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
                Tables\Columns\TextColumn::make('start_date')->label('Date')->searchable()->sortable()
                    ->getStateUsing(fn($record) => date('d/m/Y h:i A', strtotime($record->start_date))),

                Tables\Columns\TextColumn::make('policy_id')->label('Reference #')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('name')->label('Name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('qid')->label('Qatar ID')->searchable()->sortable(),

                Tables\Columns\TextColumn::make('getStatus.status')->label('Policy Status')
                    ->badge()->searchable()->sortable()
                    ->color(fn(string $state): string => match ($state) {
                        'To Renew', 'Verification', 'Expired', 'Lost' => 'danger',
                        'Paid' => 'info',
                        'Issued' => 'success',
                        'In Progress' => 'warning',
                        'Refunded' => 'gray',
                    }),
                Tables\Columns\CheckboxColumn::make('ad_verified')->label('Commit')
                    ->getStateUsing(function ($record) {
                        return $record->ad_verified == 'YES' ? 1 : 0;
                    })
                    ->searchable()->sortable()->updateStateUsing(function ($record) {
                        return $record->update(['ad_id' => \Auth::id(), 'ad_verify_date' => Carbon::now(), 'ad_verified' => 'YES']);
                    }),

                Tables\Columns\TextColumn::make('user.name')
                    ->getStateUsing(fn($record) => isset($record->user) ? $record->user->name . ' on ' . $record->ad_verify_date : 'New')
                    ->label('Commit By')->searchable()->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        '2' => 'Paid',
                        '3' => 'Issued',
                        '7' => 'Lost',
                        '4' => 'Pending',
                    ])
                    ->placeholder('Select Status')
            ])
            ->actions([
                Tables\Actions\Action::make('refund')
                    ->label('Refund')
                    ->icon('heroicon-o-arrow-path')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Confirm Refund')
                    ->modalDescription('Are you sure you want to refund this transaction?')
                    ->action(function ($record) {
                        if(!$record->transaction){
                            return;
                        }
                        $renewal = $record;
                        $record = $record->transaction;
                        // Resolve the CheckoutService (or inject via constructor)
                        if('Payment processed successfully' === $record['status']){
                            $checkout = app(CheckoutService::class);

                            $params = [
                                'payment_id' => (string)$record['transaction_no'],
                                'amount' => (string)number_format($record['amount'], 2, '.', ''),
                            ];

                            $response = $checkout->refundPayment($params);
                            try {

                                if (isset($response['result']) && $response['result'] === 'accepted') {
                                    $record->update(['refund_status' => '1']);
                                    $renewal->update(['status' => '8']);
                                    Notification::make()
                                        ->title('Refund Accepted')
                                        ->success()
                                        ->body('Payment ID: ' . $response['payment_id'])
                                        ->send();
                                } else {
                                    Notification::make()
                                        ->title('Refund Failed')
                                        ->danger()
                                        ->body($response['message'] ?? 'Unknown error')
                                        ->send();
                                }

                            } catch (\Exception $e) {
                                //print_r($record);
                                //$this->notify('danger', 'Error: ' . $e->getMessage());
                                Notification::make()
                                    ->title('Refund Error')
                                    ->danger()
                                    ->body($e->getMessage())
                                    ->send();
                            }
                        }
                        // $record->update(['refund_status' => 1]);

                        // \Filament\Notifications\Notification::make()
                        //     ->title('Transaction Refunded')
                        //     ->success()
                        //     ->send();
                    })
                   ->visible(fn($record) => $record->refund_status == 0 && $record->txn_type == 'Other'),
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
            'index' => Pages\ListRenewalManagement::route('/'),
            'create' => Pages\CreateRenewalManagement::route('/create'),
            'view' => Pages\ViewRenewalManagement::route('/{record}'),
            'edit' => Pages\EditRenewalManagement::route('/{record}/edit'),
        ];
    }
}