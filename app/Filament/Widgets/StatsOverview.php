<?php

namespace App\Filament\Widgets;

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
            Stat::make('Policies Today', Insurance::where('created_at', '>=', Carbon::today())->count()),

            Stat::make('Policies Pending', Insurance::where('created_at', '>=', Carbon::today())
                ->where('ad_verified', 'NO')->count()),

            Stat::make('Quick Pay Today', Quickpay::where('created_at', '>=', Carbon::today())->count()),
            Stat::make('Quick Pay Pending', Quickpay::where('created_at', '>=', Carbon::today())
                ->where('status', 1)->count()),

            Stat::make('Total Sales Today', 'QAR :'. Transaction::where('date', '>=', Carbon::today())
                ->where('status', 'Approved')->sum('amount')),
        ];
    }
}
