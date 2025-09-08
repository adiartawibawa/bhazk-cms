<?php

namespace App\Filament\Resources\UserResource\Widgets;

use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class UserRegistrationTrendChart extends ChartWidget
{
    protected static ?string $heading = 'User Registration Trends';

    protected static ?string $maxHeight = '300px';

    public ?string $filter = '30d';

    protected function getFilters(): ?array
    {
        return [
            '7d'  => __('resource.user.widgets.user_registration_trend.filters.7d'),
            '30d' => __('resource.user.widgets.user_registration_trend.filters.30d'),
            '90d' => __('resource.user.widgets.user_registration_trend.filters.90d'),
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
                    'label' => __('resource.user.widgets.user_registration_trend.dataset.label'),
                    'data' => $trend->map(fn(TrendValue $value) => $value->aggregate),
                    'backgroundColor' => 'rgba(59, 130, 246, 0.2)',
                    'borderColor'     => 'rgb(59, 130, 246)',
                    'borderWidth'     => 2,
                    'fill'            => true,
                    'tension'         => 0.4,
                ],
            ],
            'labels' => $trend->map(
                fn(TrendValue $value) => Carbon::parse($value->date)->format('d M')
            ),
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
                    'mode'       => 'index',
                    'intersect'  => false,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'precision' => 0,
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
