<?php

namespace App\Filament\Resources\UserResource\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class UserActivityMetrics extends BaseWidget
{
    protected static ?int $sort = 4;

    private const METRICS = [
        'today_logins' => [
            'icon'  => 'heroicon-o-arrow-right-circle',
            'color' => 'success',
            'field' => 'last_login_at',
            'period' => 'today',
        ],
        'weekly_logins' => [
            'icon'  => 'heroicon-o-calendar',
            'color' => 'primary',
            'field' => 'last_login_at',
            'period' => 'week',
        ],
        'monthly_logins' => [
            'icon'  => 'heroicon-o-chart-bar',
            'color' => 'info',
            'field' => 'last_login_at',
            'period' => 'month',
        ],
        'new_users_today' => [
            'icon'  => 'heroicon-o-user-plus',
            'color' => 'warning',
            'field' => 'created_at',
            'period' => 'today',
        ],
        'new_users_week' => [
            'icon'  => 'heroicon-o-user-group',
            'color' => 'warning',
            'field' => 'created_at',
            'period' => 'week',
        ],
        'new_users_month' => [
            'icon'  => 'heroicon-o-users',
            'color' => 'warning',
            'field' => 'created_at',
            'period' => 'month',
        ],
    ];

    protected function getStats(): array
    {
        return collect(self::METRICS)->map(function ($metric, $key) {
            $count = $this->countByPeriod($metric['field'], $metric['period']);
            $chart = $this->trendData($metric['field']);

            return Stat::make(
                __(sprintf('resource.user.widgets.user_activity_metrics.metrics.%s.title', $key)),
                $count
            )
                ->description(__(sprintf('resource.user.widgets.user_activity_metrics.metrics.%s.description', $key)))
                ->icon($metric['icon'])
                ->color($metric['color'])
                ->chart($chart);
        })->toArray();
    }

    private function countByPeriod(string $field, string $period): int
    {
        return match ($period) {
            'today' => User::whereDate($field, today())->count(),
            'week'  => User::where($field, '>=', now()->subWeek())->count(),
            'month' => User::where($field, '>=', now()->subMonth())->count(),
            default => 0,
        };
    }

    private function trendData(string $field): array
    {
        return Trend::model(User::class)
            ->between(
                start: now()->subDays(6),
                end: now()
            )
            ->perDay()
            ->count($field)
            ->map(fn(TrendValue $value) => $value->aggregate)
            ->toArray();
    }
}
