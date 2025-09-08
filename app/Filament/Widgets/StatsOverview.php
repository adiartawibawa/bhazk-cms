<?php

namespace App\Filament\Widgets;

use App\Models\Content;
use App\Models\User;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class StatsOverview extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 1;

    protected function applyDateFilters(Builder $query, $startDate, $endDate): Builder
    {
        return $query
            ->when($startDate, fn(Builder $q) => $q->whereDate('created_at', '>=', $startDate))
            ->when($endDate, fn(Builder $q) => $q->whereDate('created_at', '<=', $endDate));
    }

    protected function growthRate(int $current, int $previous): array
    {
        if ($previous === 0) {
            if ($current > 0) {
                return ['+100%', 'heroicon-o-arrow-trending-up', 'success'];
            }
            return ['0%', 'heroicon-o-minus', 'secondary'];
        }

        $diff = (($current - $previous) / $previous) * 100;
        $symbol = $diff >= 0 ? '+' : '';
        $icon = $diff >= 0 ? 'heroicon-o-arrow-trending-up' : 'heroicon-o-arrow-trending-down';
        $color = $diff >= 0 ? 'success' : 'danger';

        return [$symbol . number_format($diff, 1) . '%', $icon, $color];
    }

    public function getStats(): array
    {
        $startDate = isset($this->filters['startDate']) ? Carbon::parse($this->filters['startDate']) : now()->subDays(30);
        $endDate   = isset($this->filters['endDate']) ? Carbon::parse($this->filters['endDate']) : now();

        $periodDays = $startDate->diffInDays($endDate) ?: 30;

        // ===== USERS =====
        $totalUsers = $this->applyDateFilters(User::query(), $startDate, $endDate)->count();
        $prevUsers = $this->applyDateFilters(
            User::query(),
            $startDate->copy()->subDays($periodDays),
            $endDate->copy()->subDays($periodDays)
        )->count();

        [$userGrowth, $userIcon, $userColor] = $this->growthRate($totalUsers, $prevUsers);

        $registrationsTrend = Trend::model(User::class)
            ->between(start: $startDate, end: $endDate)
            ->perDay()
            ->count()
            ->map(fn(TrendValue $value) => $value->aggregate)
            ->toArray();

        // ===== CONTENTS =====
        $totalContents = $this->applyDateFilters(Content::query(), $startDate, $endDate)->count();
        $prevContents = $this->applyDateFilters(
            Content::query(),
            $startDate->copy()->subDays($periodDays),
            $endDate->copy()->subDays($periodDays)
        )->count();

        [$contentGrowth, $contentIcon, $contentColor] = $this->growthRate($totalContents, $prevContents);

        $contentsTrend = Trend::model(Content::class)
            ->between(start: $startDate, end: $endDate)
            ->perDay()
            ->count()
            ->map(fn(TrendValue $value) => $value->aggregate)
            ->toArray();

        // ===== TODAY'S CONTENTS =====
        $todayContents = Content::whereDate('created_at', today())->count();
        $yesterdayContents = Content::whereDate('created_at', today()->subDay())->count();

        [$todayGrowth, $todayIcon, $todayColor] = $this->growthRate($todayContents, $yesterdayContents);

        $todayTrend = Trend::model(Content::class)
            ->between(start: now()->startOfDay(), end: now()->endOfDay())
            ->perHour()
            ->count()
            ->map(fn(TrendValue $value) => $value->aggregate)
            ->toArray();

        return [
            Stat::make('Total Users', number_format($totalUsers))
                ->description("All registered users ({$userGrowth} vs prev.)")
                ->descriptionIcon($userIcon)
                ->chart($registrationsTrend)
                ->color($userColor),

            Stat::make('Total Contents', number_format($totalContents))
                ->description("Published contents ({$contentGrowth} vs prev.)")
                ->descriptionIcon($contentIcon)
                ->chart($contentsTrend)
                ->color($contentColor),

            Stat::make("Today's Contents", number_format($todayContents))
                ->description("New today ({$todayGrowth} vs yesterday)")
                ->descriptionIcon($todayIcon)
                ->chart($todayTrend)
                ->color($todayColor),
        ];
    }
}
