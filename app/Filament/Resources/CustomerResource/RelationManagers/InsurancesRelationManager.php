<?php

namespace App\Filament\Resources\CustomerResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InsurancesRelationManager extends RelationManager
{
    protected static string $relationship = 'insurances';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('policy_id')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('policy_id')
            ->columns([
                Tables\Columns\TextColumn::make('policy_id')->label('Policy ID')->searchable(),
                Tables\Columns\TextColumn::make('ins_type')->label('Type')->searchable(),
                Tables\Columns\TextColumn::make('name')->label('Name')->searchable(),
                Tables\Columns\TextColumn::make('mobile')->label('Mobile')->searchable(),
                Tables\Columns\TextColumn::make('email')->label('Email')->searchable(),
                Tables\Columns\TextColumn::make('start_date')->label('Start Date')->date(),
                Tables\Columns\TextColumn::make('end_date')->label('End Date')->date(),
                Tables\Columns\TextColumn::make('total_amount')->label('Total Amount')->money('QAR'),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'danger' => 'Cancelled',
                        'warning' => 'Pending',
                        'success' => 'Active',
                    ]),
                Tables\Columns\TextColumn::make('created_at')->label('Created At')->dateTime(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('ins_type')
                    ->label('Insurance Type')
                    ->options([
                        'Comprehensive' => 'Comprehensive',
                        'Third Party' => 'Third Party',
                    ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->modifyQueryUsing(fn (Builder $query) => $query->where('deleted', 0));
    }
}
