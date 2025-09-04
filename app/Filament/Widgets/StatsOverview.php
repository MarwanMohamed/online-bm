<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\InsuranceResource;
use App\Filament\Resources\QuickPayResource;
use App\Models\Insurance;
use App\Models\Quickpay;
use App\Models\Transaction;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Policies', Insurance::where('deleted', 0)->where(function ($query) {
                $query->where(function ($q) {
                    $q->where('pb_no', '!=', 'renewal')->orWhereNull('pb_no');
                })->where('ins_type', '!=', 'Comprehensive');
            })->count())
                ->description('All active insurance policies')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success')
                ->icon('heroicon-o-document-text')
                ->url(InsuranceResource::getUrl('index')),

            Stat::make('Policies Today', Insurance::where('created_at', '>=', Carbon::today())->count())
                ->description('New policies created today')
                ->descriptionIcon('heroicon-m-clock')
                ->chart([1, 3, 2, 5, 4, 6, 8])
                ->color('info')
                ->icon('heroicon-o-plus-circle')
                ->url(InsuranceResource::getUrl('index') . '?tableFilters[created_at][created_from]=' . Carbon::today()->format('Y-m-d')),

            Stat::make('Policies Pending', Insurance::where('ad_verified', 'NO')->where('deleted', 0)
                ->where(function ($query) {
                    $query->where('pb_no', '!=', 'renewal')
                        ->orWhereNull('pb_no');
                })->count())
                ->description('Policies awaiting verification')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->chart([5, 8, 6, 10, 7, 9, 12])
                ->color('warning')
                ->icon('heroicon-o-exclamation-triangle')
                ->url(InsuranceResource::getUrl('index') . '?tableFilters[ad_verified][value]=NO'),

            Stat::make('Quick Pay Today', Quickpay::where('created_at', '>=', Carbon::today())->count())
                ->description('Quick payments processed today')
                ->descriptionIcon('heroicon-m-bolt')
                ->chart([3, 8, 5, 12, 7, 9, 15])
                ->color('info')
                ->icon('heroicon-o-credit-card')
                ->url(QuickPayResource::getUrl('index') . '?tableFilters[created_at][created_from]=' . Carbon::today()->format('Y-m-d')),

            Stat::make('Quick Pay Pending', Quickpay::where('status', 1)->where('deleted', 0)->count())
                ->description('Quick payments awaiting processing')
                ->descriptionIcon('heroicon-m-clock')
                ->chart([2, 5, 3, 7, 4, 6, 9])
                ->color('warning')
                ->icon('heroicon-o-exclamation-circle')
                ->url(QuickPayResource::getUrl('index') . '?tableFilters[status][value]=1'),

            Stat::make('Total Sales Today', 'QAR ' . number_format(Transaction::where('date', '>=', Carbon::today())
                    ->where('status', 'Approved')->sum('amount'), 2))
                ->description('Revenue generated today')
                ->descriptionIcon('heroicon-m-banknotes')
                ->chart([100, 150, 200, 180, 250, 300, 350])
                ->color('success')
                ->icon('heroicon-o-currency-dollar'),
        ];
    }
}