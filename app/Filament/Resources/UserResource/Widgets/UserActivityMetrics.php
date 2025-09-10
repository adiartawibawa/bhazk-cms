<?php

namespace App\Filament\Resources\UserResource\Widgets;

use App\Filament\Resources\UserResource\Pages\ListUsers;
use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class UserActivityMetrics extends BaseWidget
{
    use InteractsWithPageTable;

    protected static ?int $sort = 4;

    protected function getTablePage(): string
    {
        return ListUsers::class;
    }

    private function getDateRange(): array
    {
        $filters = $this->tableFilters['date_range'] ?? [];

        $start = Carbon::parse($filters['date_range']['start_date'] ?? Carbon::now()->subDays(30));
        $end = Carbon::parse($filters['date_range']['end_date'] ?? Carbon::now());

        return [$start, $end];
    }

    private function growthRate(int $current, int $previous): array
    {
        if ($previous === 0) {
            return [
                'value' => $current > 0 ? 100 : 0,
                'icon'  => $current > 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-minus',
                'color' => $current > 0 ? 'success' : 'gray',
            ];
        }

        $rate = (($current - $previous) / $previous) * 100;

        return [
            'value' => round($rate, 1),
            'icon'  => $rate >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down',
            'color' => $rate >= 0 ? 'success' : 'danger',
        ];
    }

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
        $query = $this->getPageTableQuery();
        [$start, $end] = $this->getDateRange();

        // Hitung periode sebelumnya
        $days = $start->diffInDays($end);
        $previousStart = $start->copy()->subDays($days + 1);
        $previousEnd = $start->copy()->subDay();

        return collect(self::METRICS)->map(function ($metric, $key) use ($query, $start, $end, $previousStart, $previousEnd) {
            // Current period count
            $currentCount = $this->countByPeriod($query, $metric['field'], $metric['period'], $start, $end);

            // Previous period count
            $previousCount = $this->countByPeriod($query, $metric['field'], $metric['period'], $previousStart, $previousEnd);

            // Growth rate
            $growth = $this->growthRate($currentCount, $previousCount);

            // Trend data
            $chart = $this->trendData($query, $metric['field'], $start, $end);

            return $this->makeStat(
                $key,
                $currentCount,
                $chart,
                $metric['icon'],
                $metric['color'],
                $growth
            );
        })->toArray();
    }

    private function countByPeriod($query, string $field, string $period, $start, $end): int
    {
        $baseQuery = clone $query;

        // Apply date range filter
        $baseQuery->whereBetween($field, [$start, $end]);

        return match ($period) {
            'today' => $baseQuery->whereDate($field, today())->count(),
            'week'  => $baseQuery->where($field, '>=', now()->subWeek())->count(),
            'month' => $baseQuery->where($field, '>=', now()->subMonth())->count(),
            default => $baseQuery->count(),
        };
    }

    private function trendData($query, string $field, $start, $end): array
    {
        $trendQuery = clone $query;
        $trendQuery->whereBetween($field, [$start, $end])->reorder();

        return Trend::query($trendQuery)
            ->between($start, $end)
            ->perDay()
            ->count($field)
            ->map(fn(TrendValue $value) => $value->aggregate)
            ->toArray();
    }

    private function makeStat(
        string $key,
        int $value,
        ?array $chart,
        string $icon,
        string $color,
        ?array $growth = null
    ): Stat {
        $label = __('resource.user.widgets.user_activity_metrics.metrics.' . $key . '.title');
        $description = __('resource.user.widgets.user_activity_metrics.metrics.' . $key . '.description');

        $stat = Stat::make($label, number_format($value))
            ->description($description)
            ->icon($icon)
            ->color($color);

        if ($growth) {
            $valueText = $growth['value'] >= 0 ? '+' . $growth['value'] : (string) $growth['value'];

            $stat->description("{$description} | Growth: {$valueText}% vs prev")
                ->descriptionIcon($growth['icon'])
                ->extraAttributes([
                    'class' => 'text-' . $growth['color'] . '-600',
                ]);
        }

        if ($chart) {
            $stat->chart($chart);
        }

        return $stat;
    }
}
