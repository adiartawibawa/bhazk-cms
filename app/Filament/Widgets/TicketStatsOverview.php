<?php

namespace App\Filament\Widgets;

use App\Models\Ticket;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class TicketStatsOverview extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 3;

    protected function applyDateFilters(Builder $query, $startDate, $endDate): Builder
    {
        return $query
            ->when($startDate, fn(Builder $q) => $q->whereDate('created_at', '>=', $startDate))
            ->when($endDate, fn(Builder $q) => $q->whereDate('created_at', '<=', $endDate));
    }

    /**
     * Calculate growth rate with description, icon, and color
     */
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

    protected function getStats(): array
    {
        $startDate = isset($this->filters['startDate'])
            ? Carbon::parse($this->filters['startDate'])
            : now()->subDays(30);

        $endDate = isset($this->filters['endDate'])
            ? Carbon::parse($this->filters['endDate'])
            : now();

        $previousStart = (clone $startDate)->subDays($endDate->diffInDays($startDate) + 1);
        $previousEnd   = (clone $startDate)->subDay();

        // ---- Stats sekarang
        $totalTickets      = Ticket::whereBetween('created_at', [$startDate, $endDate])->count();
        $openTickets       = Ticket::where('status', Ticket::STATUS_OPEN)->whereBetween('created_at', [$startDate, $endDate])->count();
        $inProgressTickets = Ticket::where('status', Ticket::STATUS_IN_PROGRESS)->whereBetween('created_at', [$startDate, $endDate])->count();
        $resolvedTickets   = Ticket::where('status', Ticket::STATUS_RESOLVED)->whereBetween('created_at', [$startDate, $endDate])->count();

        // ---- Stats periode sebelumnya
        $prevTotal      = Ticket::whereBetween('created_at', [$previousStart, $previousEnd])->count();
        $prevOpen       = Ticket::where('status', Ticket::STATUS_OPEN)->whereBetween('created_at', [$previousStart, $previousEnd])->count();
        $prevInProgress = Ticket::where('status', Ticket::STATUS_IN_PROGRESS)->whereBetween('created_at', [$previousStart, $previousEnd])->count();
        $prevResolved   = Ticket::where('status', Ticket::STATUS_RESOLVED)->whereBetween('created_at', [$previousStart, $previousEnd])->count();

        // ---- Growth Rate
        [$totalGrowth, $totalIcon, $totalColor]           = $this->growthRate($totalTickets, $prevTotal);
        [$openGrowth, $openIcon, $openColor]              = $this->growthRate($openTickets, $prevOpen);
        [$inProgressGrowth, $inProgressIcon, $inProgColor] = $this->growthRate($inProgressTickets, $prevInProgress);
        [$resolvedGrowth, $resolvedIcon, $resolvedColor]  = $this->growthRate($resolvedTickets, $prevResolved);

        // ---- Tren untuk mini chart
        $totalTrend = Trend::model(Ticket::class)->between($startDate, $endDate)->perDay()->count();
        $openTrend  = Trend::model(Ticket::class)->between($startDate, $endDate)->perDay()->count('id', 'created_at', function ($query) {
            $query->where('status', Ticket::STATUS_OPEN);
        });
        $inProgressTrend = Trend::model(Ticket::class)->between($startDate, $endDate)->perDay()->count('id', 'created_at', function ($query) {
            $query->where('status', Ticket::STATUS_IN_PROGRESS);
        });
        $resolvedTrend = Trend::model(Ticket::class)->between($startDate, $endDate)->perDay()->count('id', 'created_at', function ($query) {
            $query->where('status', Ticket::STATUS_RESOLVED);
        });

        // ---- Avg response time (global, tidak difilter periode sebelumnya)
        $avgResponseTime = Ticket::whereNotNull('first_response_at')
            ->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, created_at, first_response_at)) as avg_time')
            ->value('avg_time');

        return [
            Stat::make('Total Tickets', $totalTickets)
                ->description($totalGrowth . ' vs prev period')
                ->descriptionIcon($totalIcon)
                ->icon('heroicon-o-ticket')
                ->color($totalColor)
                ->chart($totalTrend->map(fn(TrendValue $value) => $value->aggregate)->toArray()),

            Stat::make('Open Tickets', $openTickets)
                ->description($openGrowth . ' vs prev period')
                ->descriptionIcon($openIcon)
                ->icon('heroicon-o-exclamation-circle')
                ->color($openColor)
                ->chart($openTrend->map(fn(TrendValue $value) => $value->aggregate)->toArray()),

            Stat::make('In Progress', $inProgressTickets)
                ->description($inProgressGrowth . ' vs prev period')
                ->descriptionIcon($inProgressIcon)
                ->icon('heroicon-o-cog-6-tooth')
                ->color($inProgColor)
                ->chart($inProgressTrend->map(fn(TrendValue $value) => $value->aggregate)->toArray()),

            Stat::make('Resolved', $resolvedTickets)
                ->description($resolvedGrowth . ' vs prev period')
                ->descriptionIcon($resolvedIcon)
                ->icon('heroicon-o-check-circle')
                ->color($resolvedColor)
                ->chart($resolvedTrend->map(fn(TrendValue $value) => $value->aggregate)->toArray()),

            Stat::make('Avg Response Time', $avgResponseTime ? round($avgResponseTime) . ' mins' : 'N/A')
                ->description('Average first response time')
                ->icon('heroicon-o-clock')
                ->color('info'),
        ];
    }
}
