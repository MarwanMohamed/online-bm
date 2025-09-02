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
use App\Filament\Resources\InsuranceResource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Filament\Notifications\Notification;

class InsuranceWidgets extends BaseWidget
{
    protected int|string|array $columnSpan = 2;
    protected static ?int $sort = 2;
    
    public static string $resource = Insurance::class;
    
    protected function getTableQuery(): Builder
    {
        return Insurance::where('deleted', 0)
            ->where(function ($query) {
                $query->where('pb_no', '!=', 'renewal')
                    ->orWhereNull('pb_no');
            })
            ->with(['user', 'getStatus'])
            ->orderBy('created_at', 'desc');
    }

    protected function getDefaultTableSortColumn(): ?string
    {
        return 'created_at';
    }

    protected function getDefaultTableSortDirection(): ?string
    {
        return 'desc';
    }


    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('created_at')->label('Date')->searchable()->sortable()
                ->getStateUsing(fn($record) => date('d/m/Y h:i A', strtotime($record->created_at)))
                ->sortable(),

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
                    default => 'secondary',
                }),
            
            Tables\Columns\CheckboxColumn::make('ad_verified')->label('Commit')
                ->getStateUsing(function ($record) {
                    return $record->ad_verified == 'YES' ? 1 : 0;
                })
                ->sortable()
                ->searchable()
                ->updateStateUsing(function ($record, $state) {
                    if ($state) {
                        return $record->update([
                            'ad_id' => Auth::id(), 
                            'ad_verify_date' => Carbon::now(), 
                            'ad_verified' => 'YES'
                        ]);
                    } else {
                        return $record->update([
                            'ad_id' => null, 
                            'ad_verify_date' => null, 
                            'ad_verified' => 'NO'
                        ]);
                    }
                }),

            Tables\Columns\TextColumn::make('ad_verify_date')->label('Commit By')
                ->getStateUsing(function ($record) {
                    if ($record->ad_verified == 'YES' && $record->ad_id && $record->ad_verify_date) {
                        $agent = \App\Models\User::find($record->ad_id);
                        $agentName = $agent ? $agent->name : 'Unknown Agent';
                        $date = Carbon::parse($record->ad_verify_date)->format('d/m/Y h:i A');
                        return $agentName . ' - ' . $date;
                    }
                    return '-';
                })
                ->sortable()
                ->searchable(),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Tables\Actions\ViewAction::make()
                ->url(fn($record) => InsuranceResource::getUrl('view', ['record' => $record]))
                ->openUrlInNewTab(),
            Tables\Actions\EditAction::make()
                ->url(fn($record) => InsuranceResource::getUrl('edit', ['record' => $record]))
                ->openUrlInNewTab(),
            Tables\Actions\DeleteAction::make()
                ->requiresConfirmation()
                ->action(fn($record) => $record->delete()),
        ];
    }

    protected function getTableActionsPosition(): ?string
    {
        return 'before_columns';
    }

    public function getTable(): Table
    {
        return parent::getTable()
            ->striped()
            ->defaultPaginationPageOption(25)
            ->actionsColumnLabel('Policy Type');
    }

    protected function getTableFilters(): array
    {
        return [

        ];
    }
}
