<?php

namespace App\Filament\Resources\TicketResource\Widgets;

use App\Models\Ticket;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class ResponseTimeMetrics extends BaseWidget
{
    protected static ?int $sort = 4;

    protected function getStats(): array
    {
        $responseTimes = Ticket::whereNotNull('first_response_at')
            ->select([
                DB::raw('AVG(TIMESTAMPDIFF(MINUTE, created_at, first_response_at)) as avg_time'),
                DB::raw('MIN(TIMESTAMPDIFF(MINUTE, created_at, first_response_at)) as min_time'),
                DB::raw('MAX(TIMESTAMPDIFF(MINUTE, created_at, first_response_at)) as max_time'),
            ])
            ->first();

        $urgentResponseTime = Ticket::where('priority', Ticket::PRIORITY_URGENT)
            ->whereNotNull('first_response_at')
            ->avg(DB::raw('TIMESTAMPDIFF(MINUTE, created_at, first_response_at)'));

        return [
            Stat::make('Avg Response Time', $responseTimes->avg_time ? round($responseTimes->avg_time) . ' mins' : 'N/A')
                ->description('Across all tickets')
                ->icon('heroicon-o-clock')
                ->color('primary'),

            Stat::make('Fastest Response', $responseTimes->min_time ? round($responseTimes->min_time) . ' mins' : 'N/A')
                ->description('Quickest response time')
                ->icon('heroicon-o-bolt')
                ->color('success'),

            Stat::make('Urgent Ticket Avg', $urgentResponseTime ? round($urgentResponseTime) . ' mins' : 'N/A')
                ->description('For urgent priority tickets')
                ->icon('heroicon-o-exclamation-triangle')
                ->color('danger'),
        ];
    }
}
