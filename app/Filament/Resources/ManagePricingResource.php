<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ManagePricingResource\Pages;
use App\Filament\Resources\ManagePricingResource\RelationManagers;
use App\Models\ManagePricing;
use App\Models\Thirdparty;
use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ManagePricingResource extends Resource
{
    protected static ?string $model = Thirdparty::class;

    protected static ?string $navigationIcon = 'phosphor-globe-simple';
    protected static string $view = 'filament.pages.general-settings';
    protected static ?string $label = 'Manage Pricing';
    protected static ?int $navigationSort = 7;

    public static function getEloquentQuery(): Builder
    {
        return Thirdparty::where('parent_id', 0)->where('deleted', 0);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([


                Select::make('opt_1')->label('Type of Vehicle')->required()->hiddenOn('create')
                    ->options(Thirdparty::where('id', $form->model->id ?? 0)->pluck('value', 'id')->toArray())
                    ->live(),

                Select::make('opt_2')->hiddenOn('create')
                    ->options(fn(Get $get) => Thirdparty::where('parent_id', $form->model->id ?? 0)->pluck('value', 'id'))
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


                Forms\Components\TextInput::make('value')->label('Name')->columnSpanFull()->placeholder('Enter Name'),
                Checkbox::make('final')->live()->label('Final (Check this to add price)')->columnSpanFull(),
                Forms\Components\TextInput::make('name')->hidden(function (Get $get) {
                    return $get('final');
                })->label('Sub Class Title')->columnSpanFull()->placeholder('Enter Sub Class Title'),
                Forms\Components\TextInput::make('ar_name')->hidden(function (Get $get) {
                    return $get('final');
                })->columnSpanFull()->placeholder('Enter Sub Class Title Arabic')->label('Sub Class Title Arabic'),

                Forms\Components\TextInput::make('base')->hidden(function (Get $get) {
                    return !$get('final');
                })->label('Base Amount')->placeholder('Enter Base Amount')->columnSpanFull(),
                Forms\Components\TextInput::make('passenger')->hidden(function (Get $get) {
                    return !$get('final');
                })->label('Pass Amount')->placeholder('Enter Passenger Amount')->columnSpanFull(),
                Forms\Components\TextInput::make('max_pass')->hidden(function (Get $get) {
                    return !$get('final');
                })->label('Max Passenges')->placeholder('Enter Max Passenges')->columnSpanFull(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('value')->label('Type of Vehicle')
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
            'index' => Pages\ListManagePricings::route('/'),
            'create' => Pages\CreateManagePricing::route('/create'),
            'edit' => Pages\EditManagePricing::route('/{record}/edit'),
        ];
    }
}
