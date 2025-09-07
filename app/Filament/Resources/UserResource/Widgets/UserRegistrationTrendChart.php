<?php

namespace App\Filament\Resources\UserResource\Widgets;

use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class UserRegistrationTrendChart extends ChartWidget
{
    protected static ?string $heading = 'User Registration Trend';
    protected static ?string $maxHeight = '300px';

    /**
     * Default filter (last 30 days).
     */
    public ?string $filter = '30d';

    protected function getFilters(): ?array
    {
        return [
            '7d' => 'Last 7 Days',
            '30d' => 'Last 30 Days',
            '90d' => 'Last 90 Days',
        ];
    }

    protected function getData(): array
    {
        $days = match ($this->filter) {
            '7d' => 7,
            '90d' => 90,
            default => 30,
        };

        $trend = Trend::model(User::class)
            ->between(
                start: now()->subDays($days - 1),
                end: now(),
            )
            ->perDay()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'New Registrations',
                    'data' => $trend->map(fn(TrendValue $value) => $value->aggregate),
                    'backgroundColor' => 'rgba(59, 130, 246, 0.2)', // soft blue fill
                    'borderColor' => 'rgb(59, 130, 246)',           // blue line
                    'borderWidth' => 2,
                    'fill' => true,
                    'tension' => 0.4, // smooth line
                ],
            ],
            'labels' => $trend->map(fn(TrendValue $value) => Carbon::parse($value->date)->format('d M')),
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
                    'display' => false,
                ],
                'tooltip' => [
                    'mode' => 'index',
                    'intersect' => false,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'precision' => 0, // angka bulat
                    ],
                ],
                'x' => [
                    'grid' => [
                        'display' => false,
                    ],
                ],
            ],
        ];
    }
}
