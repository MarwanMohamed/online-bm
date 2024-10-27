<?php

namespace App\Filament\Widgets;

use App\Models\Discount;
use App\Models\Insurance;
use App\Models\Provider;
use Closure;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\Filter;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;

class InsuranceWidgets extends BaseWidget
{
    protected int|string|array $columnSpan = 2;
    protected static ?int $sort = 2;

    protected function getTableQuery(): Builder
    {
        return Insurance::where('deleted', 0)
            ->where('pb_no', '!=', 'renewal')
            ->with('user')->orderBy('created_at', 'desc');
    }


    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('id')->label('ID')->searchable()->sortable(),
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
        ];
    }

    protected function getTableActions(): array
    {
        return [

        ];
    }

    protected function getTableFilters(): array
    {
        return [

        ];
    }
}
