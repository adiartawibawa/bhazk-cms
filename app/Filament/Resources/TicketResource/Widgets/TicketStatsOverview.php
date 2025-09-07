<?php

namespace App\Filament\Resources\TicketResource\Widgets;

use App\Models\Ticket;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TicketStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalTickets = Ticket::count();
        $openTickets = Ticket::where('status', Ticket::STATUS_OPEN)->count();
        $inProgressTickets = Ticket::where('status', Ticket::STATUS_IN_PROGRESS)->count();
        $resolvedTickets = Ticket::where('status', Ticket::STATUS_RESOLVED)->count();
        $closedTickets = Ticket::where('status', Ticket::STATUS_CLOSED)->count();

        $avgResponseTime = Ticket::whereNotNull('first_response_at')
            ->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, created_at, first_response_at)) as avg_time')
            ->first()
            ->avg_time;

        return [
            Stat::make('Total Tickets', $totalTickets)
                ->description('All tickets in the system')
                ->icon('heroicon-o-ticket')
                ->color('primary'),

            Stat::make('Open Tickets', $openTickets)
                ->description('Requiring attention')
                ->icon('heroicon-o-exclamation-circle')
                ->color('danger')
                ->descriptionIcon('heroicon-m-arrow-trending-up'),

            Stat::make('In Progress', $inProgressTickets)
                ->description('Being worked on')
                ->icon('heroicon-o-cog-6-tooth')
                ->color('warning'),

            Stat::make('Resolved', $resolvedTickets)
                ->description('Successfully resolved')
                ->icon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make('Closed', $closedTickets)
                ->description('Completed tickets')
                ->icon('heroicon-o-lock-closed')
                ->color('gray'),

            Stat::make('Avg Response Time', $avgResponseTime ? round($avgResponseTime) . ' mins' : 'N/A')
                ->description('Average first response time')
                ->icon('heroicon-o-clock')
                ->color('info'),
        ];
    }
}
