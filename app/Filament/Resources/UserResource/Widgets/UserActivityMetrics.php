<?php

namespace App\Filament\Resources\UserResource\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class UserActivityMetrics extends BaseWidget
{
    protected static ?int $sort = 4;

    private const METRICS = [
        [
            'title' => 'Today Logins',
            'description' => 'Users logged in today',
            'icon' => 'heroicon-o-arrow-right-circle',
            'color' => 'success',
            'field' => 'last_login_at',
            'period' => 'today',
        ],
        [
            'title' => 'Weekly Logins',
            'description' => 'Active users this week',
            'icon' => 'heroicon-o-calendar',
            'color' => 'primary',
            'field' => 'last_login_at',
            'period' => 'week',
        ],
        [
            'title' => 'Monthly Logins',
            'description' => 'Active users this month',
            'icon' => 'heroicon-o-chart-bar',
            'color' => 'info',
            'field' => 'last_login_at',
            'period' => 'month',
        ],
        [
            'title' => 'New Users Today',
            'description' => 'Registered today',
            'icon' => 'heroicon-o-user-plus',
            'color' => 'warning',
            'field' => 'created_at',
            'period' => 'today',
        ],
        [
            'title' => 'New Users This Week',
            'description' => 'Registered this week',
            'icon' => 'heroicon-o-user-group',
            'color' => 'warning',
            'field' => 'created_at',
            'period' => 'week',
        ],
        [
            'title' => 'New Users This Month',
            'description' => 'Registered this month',
            'icon' => 'heroicon-o-users',
            'color' => 'warning',
            'field' => 'created_at',
            'period' => 'month',
        ],
    ];

    protected function getStats(): array
    {
        return collect(self::METRICS)->map(function ($metric) {
            $count = $this->countByPeriod($metric['field'], $metric['period']);
            $chart = $this->trendData($metric['field']);

            return Stat::make($metric['title'], $count)
                ->description($metric['description'])
                ->icon($metric['icon'])
                ->color($metric['color'])
                ->chart($chart);
        })->toArray();
    }

    /**
     * Hitung jumlah user berdasarkan field (created_at / last_login_at) dan periode.
     */
    private function countByPeriod(string $field, string $period): int
    {
        return match ($period) {
            'today' => User::whereDate($field, today())->count(),
            'week'  => User::where($field, '>=', now()->subWeek())->count(),
            'month' => User::where($field, '>=', now()->subMonth())->count(),
            default => 0,
        };
    }

    /**
     * Ambil data tren harian untuk 7 hari terakhir (untuk sparkline chart).
     */
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
