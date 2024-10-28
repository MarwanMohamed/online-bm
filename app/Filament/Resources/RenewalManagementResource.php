<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RenewalManagementResource\Pages;
use App\Models\Insurance;
use Carbon\Carbon;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

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
            ->with('user')->orderBy('id', 'desc');
    }

//    public static function getNavigationBadge(): ?string
//    {
//        return static::getModel()::where('deleted', 0)->where('pb_no', "renewal")->count();
//    }

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
            'edit' => Pages\EditRenewalManagement::route('/{record}/edit'),
        ];
    }
}