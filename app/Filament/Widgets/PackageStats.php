<?php

namespace App\Filament\Widgets;

use App\Models\Package;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PackageStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Packages', Package::count())
                ->description('All time packages')
                ->descriptionIcon('heroicon-m-cube')
                ->color('primary')
                ->chart(Package::query()
                    ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                    ->groupBy('date')
                    ->pluck('count')
                    ->toArray()),

            Stat::make('Active Packages', Package::whereIn('status', ['pending', 'accepted', 'queued', 'sending', 'in_transit'])->count())
                ->description('Currently active shipments')
                ->descriptionIcon('heroicon-m-truck')
                ->color('warning'),

            Stat::make('Delivered', Package::where('status', 'delivered')->count())
                ->description('Successfully delivered')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success'),
        ];
    }
}
