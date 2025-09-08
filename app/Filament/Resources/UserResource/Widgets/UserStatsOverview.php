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
            'active'        => User::where('is_active', true)->count(),
            'inactive'      => User::where('is_active', false)->count(),
            'admin'         => User::role(User::ROLE_ADMIN)->count(),
            'neverLoggedIn' => User::whereNull('last_login_at')->count(),
        ];

        $start = Carbon::now()->subDays(6);
        $end   = Carbon::now();

        $registrationsTrend   = $this->trendData(Trend::model(User::class)->between($start, $end)->perDay()->count());
        $activeTrend          = $this->trendData(Trend::query(User::where('is_active', true))->between($start, $end)->perDay()->count());
        $inactiveTrend        = $this->trendData(Trend::query(User::where('is_active', false))->between($start, $end)->perDay()->count());
        $adminTrend           = $this->trendData(Trend::query(User::role(User::ROLE_ADMIN))->between($start, $end)->perDay()->count());
        $neverLoggedInTrend   = $this->trendData(Trend::query(User::whereNull('last_login_at'))->between($start, $end)->perDay()->count());

        return [
            $this->makeStat('total_users', $totalUsers, $registrationsTrend, 'heroicon-o-users', 'primary'),
            $this->makeStat('active_users', $stats['active'], $activeTrend, 'heroicon-o-check-circle', 'success', 'heroicon-m-arrow-trending-up'),
            $this->makeStat('inactive_users', $stats['inactive'], $inactiveTrend, 'heroicon-o-x-circle', 'danger'),
            $this->makeStat('admin_users', $stats['admin'], $adminTrend, 'heroicon-o-shield-check', 'warning'),
            $this->makeStat('never_logged_in', $stats['neverLoggedIn'], $neverLoggedInTrend, 'heroicon-o-clock', 'gray'),
        ];
    }

    private function makeStat(
        string $key,
        int $value,
        ?array $chart,
        string $icon,
        string $color,
        ?string $descriptionIcon = null
    ): Stat {
        $label       = __('resource.user.widgets.user_stats_overview.stats.' . $key . '.label');
        $description = __('resource.user.widgets.user_stats_overview.stats.' . $key . '.description');

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

    private function trendData($trend): array
    {
        return $trend->map(fn(TrendValue $value) => $value->aggregate)->toArray();
    }
}
