<?php

namespace App\Filament\Widgets;

use App\Models\Package;
use App\Models\SmsMessage;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total SMS', SmsMessage::count())
                ->description('Total messages sent')
                ->descriptionIcon('heroicon-m-chat-bubble-left')
                ->color('success')
                ->chart(SmsMessage::query()
                    ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                    ->groupBy('date')
                    ->pluck('count')
                    ->toArray()),

            Stat::make('SMS Success Rate', function() {
                $total = SmsMessage::count();
                $success = SmsMessage::where('status', 'sent')->count();
                return $total ? round(($success / $total) * 100) . '%' : '0%';
            })
                ->description('Successfully delivered messages')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Failed SMS', SmsMessage::where('status', 'failed')->count())
                ->description('Failed message deliveries')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger'),
        ];
    }
}
