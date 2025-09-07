<?php

namespace App\Filament\Resources\UserResource\Widgets;

use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class UserStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalUsers = User::count();

        $stats = [
            'active' => User::where('is_active', true)->count(),
            'inactive' => User::where('is_active', false)->count(),
            'admin' => User::role(User::ROLE_ADMIN)->count(),
            'neverLoggedIn' => User::whereNull('last_login_at')->count(),
        ];

        // Range data 7 hari terakhir
        $start = Carbon::now()->subDays(6);
        $end = Carbon::now();

        // Dinamis chart data pakai Trend
        $registrationsTrend = $this->trendData(
            Trend::model(User::class)->between($start, $end)->perDay()->count()
        );

        $activeTrend = $this->trendData(
            Trend::query(User::where('is_active', true))
                ->between($start, $end)
                ->perDay()
                ->count()
        );

        $inactiveTrend = $this->trendData(
            Trend::query(User::where('is_active', false))
                ->between($start, $end)
                ->perDay()
                ->count()
        );

        $adminTrend = $this->trendData(
            Trend::query(User::role(User::ROLE_ADMIN))
                ->between($start, $end)
                ->perDay()
                ->count()
        );

        $neverLoggedInTrend = $this->trendData(
            Trend::query(User::whereNull('last_login_at'))
                ->between($start, $end)
                ->perDay()
                ->count()
        );

        return [
            $this->makeStat(
                label: 'Total Users',
                value: $totalUsers,
                description: 'All registered users',
                icon: 'heroicon-o-users',
                color: 'primary',
                chart: $registrationsTrend,
            ),

            $this->makeStat(
                label: 'Active Users',
                value: $stats['active'],
                description: 'Enabled accounts',
                icon: 'heroicon-o-check-circle',
                color: 'success',
                descriptionIcon: 'heroicon-m-arrow-trending-up',
                chart: $activeTrend,
            ),

            $this->makeStat(
                label: 'Inactive Users',
                value: $stats['inactive'],
                description: 'Disabled accounts',
                icon: 'heroicon-o-x-circle',
                color: 'danger',
                chart: $inactiveTrend,
            ),

            $this->makeStat(
                label: 'Admin Users',
                value: $stats['admin'],
                description: 'Administrator accounts',
                icon: 'heroicon-o-shield-check',
                color: 'warning',
                chart: $adminTrend,
            ),

            $this->makeStat(
                label: 'Never Logged In',
                value: $stats['neverLoggedIn'],
                description: 'Accounts that never logged in',
                icon: 'heroicon-o-clock',
                color: 'gray',
                chart: $neverLoggedInTrend,
            ),
        ];
    }

    /**
     * Helper untuk membuat Stat agar kode lebih clean.
     */
    private function makeStat(
        string $label,
        int $value,
        string $description,
        string $icon,
        string $color,
        ?string $descriptionIcon = null,
        ?array $chart = null,
    ): Stat {
        $stat = Stat::make($label, number_format($value))
            ->description($description)
            ->icon($icon)
            ->color($color);

        if ($descriptionIcon) {
            $stat->descriptionIcon($descriptionIcon);
        }

        if ($chart) {
            $stat->chart($chart);
        }

        return $stat;
    }

    /**
     * Convert Trend collection ke array chart.
     */
    private function trendData($trend): array
    {
        return $trend->map(fn(TrendValue $value) => $value->aggregate)->toArray();
    }
}
