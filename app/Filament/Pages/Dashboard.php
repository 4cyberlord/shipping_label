<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\DeliveryChart;
use App\Filament\Widgets\LatestPackages;
use App\Filament\Widgets\PackageStats;
use App\Filament\Widgets\StatsOverview;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected function getHeaderWidgets(): array
    {
        return [
            StatsOverview::class,
            PackageStats::class,
        ];
    }

    public function getWidgets(): array
    {
        return [
            DeliveryChart::class,
            LatestPackages::class,
        ];
    }
}
