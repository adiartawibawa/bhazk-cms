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

class UserStatsOverview extends BaseWidget
{
    use InteractsWithPageTable;

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

    protected function getStats(): array
    {
        $query = $this->getPageTableQuery();
        [$start, $end] = $this->getDateRange();

        // hitung periode sebelumnya
        $days = $start->diffInDays($end);
        $previousStart = $start->copy()->subDays($days + 1);
        $previousEnd = $start->copy()->subDay();

        // Clone query untuk menghindari modifikasi query utama
        $baseQuery = clone $query;

        // current stats - gunakan query yang sudah difilter
        $currentStats = [
            'totalUsers'    => $baseQuery->whereBetween('created_at', [$start, $end])->count(),
            'active'        => (clone $baseQuery)->whereBetween('created_at', [$start, $end])->where('is_active', true)->count(),
            'inactive'      => (clone $baseQuery)->whereBetween('created_at', [$start, $end])->where('is_active', false)->count(),
            'admin'         => (clone $baseQuery)->whereBetween('created_at', [$start, $end])->whereHas('roles', fn($q) => $q->where('name', User::ROLE_ADMIN))->count(),
            'neverLoggedIn' => (clone $baseQuery)->whereBetween('created_at', [$start, $end])->whereNull('last_login_at')->count(),
        ];

        // previous stats - buat query baru untuk periode sebelumnya
        $previousBaseQuery = User::query();

        // Terapkan filter yang sama dari page table jika ada
        if (method_exists($this, 'applyFiltersToQuery')) {
            $previousBaseQuery = $this->applyFiltersToQuery($previousBaseQuery);
        }

        $previousStats = [
            'prevTotal'        => $previousBaseQuery->whereBetween('created_at', [$previousStart, $previousEnd])->count(),
            'prevActive'       => (clone $previousBaseQuery)->whereBetween('created_at', [$previousStart, $previousEnd])->where('is_active', true)->count(),
            'prevInactive'     => (clone $previousBaseQuery)->whereBetween('created_at', [$previousStart, $previousEnd])->where('is_active', false)->count(),
            'prevAdmin'        => (clone $previousBaseQuery)->whereBetween('created_at', [$previousStart, $previousEnd])->whereHas('roles', fn($q) => $q->where('name', User::ROLE_ADMIN))->count(),
            'prevNeverLoggedIn' => (clone $previousBaseQuery)->whereBetween('created_at', [$previousStart, $previousEnd])->whereNull('last_login_at')->count(),
        ];

        // growth rates
        $totalGrowth    = $this->growthRate($currentStats['totalUsers'], $previousStats['prevTotal']);
        $activeGrowth   = $this->growthRate($currentStats['active'], $previousStats['prevActive']);
        $inactiveGrowth = $this->growthRate($currentStats['inactive'], $previousStats['prevInactive']);
        $adminGrowth    = $this->growthRate($currentStats['admin'], $previousStats['prevAdmin']);
        $neverGrowth    = $this->growthRate($currentStats['neverLoggedIn'], $previousStats['prevNeverLoggedIn']);

        // trend data - pastikan menggunakan query yang benar dengan filter created_at
        $registrationsTrend   = $this->trendData(Trend::query((clone $query)->whereBetween('created_at', [$start, $end])->reorder())->between($start, $end)->perDay()->count());
        $activeTrend          = $this->trendData(Trend::query((clone $query)->whereBetween('created_at', [$start, $end])->where('is_active', true)->reorder())->between($start, $end)->perDay()->count());
        $inactiveTrend        = $this->trendData(Trend::query((clone $query)->whereBetween('created_at', [$start, $end])->where('is_active', false)->reorder())->between($start, $end)->perDay()->count());
        $adminTrend           = $this->trendData(Trend::query((clone $query)->whereBetween('created_at', [$start, $end])->whereHas('roles', fn($q) => $q->where('name', User::ROLE_ADMIN))->reorder())->between($start, $end)->perDay()->count());
        $neverLoggedInTrend   = $this->trendData(Trend::query((clone $query)->whereBetween('created_at', [$start, $end])->whereNull('last_login_at')->reorder())->between($start, $end)->perDay()->count());

        return [
            $this->makeStat('total_users', $currentStats['totalUsers'], $registrationsTrend, 'heroicon-o-users', 'primary', $totalGrowth),
            $this->makeStat('active_users', $currentStats['active'], $activeTrend, 'heroicon-o-check-circle', 'success', $activeGrowth),
            $this->makeStat('inactive_users', $currentStats['inactive'], $inactiveTrend, 'heroicon-o-x-circle', 'danger', $inactiveGrowth),
            $this->makeStat('admin_users', $currentStats['admin'], $adminTrend, 'heroicon-o-shield-check', 'warning', $adminGrowth),
            $this->makeStat('never_logged_in', $currentStats['neverLoggedIn'], $neverLoggedInTrend, 'heroicon-o-clock', 'gray', $neverGrowth),
        ];
    }

    private function makeStat(
        string $key,
        int $value,
        ?array $chart,
        string $icon,
        string $color,
        ?array $growth = null
    ): Stat {
        $label = __('resource.user.widgets.user_stats_overview.stats.' . $key . '.label');

        $stat = Stat::make($label, number_format($value))
            ->icon($icon)
            ->color($color); // warna utama tidak berubah

        if ($growth) {
            $valueText = $growth['value'] >= 0 ? '+' . $growth['value'] : (string) $growth['value'];

            $stat->description("Growth: {$valueText}% vs prev")
                ->descriptionIcon($growth['icon'])
                ->extraAttributes([
                    'class' => 'text-' . $growth['color'] . '-600', // hanya warna teks growth
                ]);
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
