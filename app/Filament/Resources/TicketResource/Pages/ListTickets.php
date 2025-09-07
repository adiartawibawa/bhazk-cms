<?php

namespace App\Filament\Resources\TicketResource\Pages;

use App\Filament\Resources\TicketResource;
use App\Filament\Resources\TicketResource\Widgets\MyAssignedTickets;
use App\Filament\Resources\TicketResource\Widgets\RecentTicketsTable;
use App\Filament\Resources\TicketResource\Widgets\ResponseTimeMetrics;
use App\Filament\Resources\TicketResource\Widgets\TicketPriorityChart;
use App\Filament\Resources\TicketResource\Widgets\TicketStatsOverview;
use App\Filament\Resources\TicketResource\Widgets\TicketStatusChart;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTickets extends ListRecords
{
    protected static string $resource = TicketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            TicketStatsOverview::class,
            TicketStatusChart::class,
            TicketPriorityChart::class,
            ResponseTimeMetrics::class,
            MyAssignedTickets::class,
        ];
    }
}
