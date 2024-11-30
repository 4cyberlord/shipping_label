<?php

namespace App\Filament\Widgets;

use App\Models\Package;
use App\Models\SmsMessage;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class DeliveryChart extends ChartWidget
{
    protected static ?string $heading = 'Performance Overview';
    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        $packages = Trend::model(Package::class)
            ->between(
                start: now()->subDays(30),
                end: now(),
            )
            ->perDay()
            ->count();

        $messages = Trend::model(SmsMessage::class)
            ->between(
                start: now()->subDays(30),
                end: now(),
            )
            ->perDay()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Packages',
                    'data' => $packages->map(fn (TrendValue $value) => $value->aggregate),
                    'borderColor' => '#3b82f6',  // Blue
                    'backgroundColor' => '#3b82f680',
                ],
                [
                    'label' => 'SMS Messages',
                    'data' => $messages->map(fn (TrendValue $value) => $value->aggregate),
                    'borderColor' => '#10b981',  // Green
                    'backgroundColor' => '#10b98180',
                ],
            ],
            'labels' => $packages->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'top',
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
            ],
        ];
    }
}
