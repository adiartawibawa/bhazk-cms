<?php

namespace App\Filament\Resources\TicketResource\Widgets;

use App\Models\Ticket;
use Filament\Widgets\ChartWidget;

class TicketStatusChart extends ChartWidget
{
    protected static ?string $heading = 'Tickets by Status';
    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $statusCounts = Ticket::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $labels = [
            Ticket::STATUS_OPEN => 'Open',
            Ticket::STATUS_IN_PROGRESS => 'In Progress',
            Ticket::STATUS_ON_HOLD => 'On Hold',
            Ticket::STATUS_RESOLVED => 'Resolved',
            Ticket::STATUS_CLOSED => 'Closed',
        ];

        $data = [];
        $backgroundColors = [
            Ticket::STATUS_OPEN => '#ef4444',
            Ticket::STATUS_IN_PROGRESS => '#f59e0b',
            Ticket::STATUS_ON_HOLD => '#3b82f6',
            Ticket::STATUS_RESOLVED => '#10b981',
            Ticket::STATUS_CLOSED => '#6b7280',
        ];

        foreach ($labels as $key => $label) {
            $data[] = $statusCounts[$key] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Tickets by Status',
                    'data' => $data,
                    'backgroundColor' => array_values($backgroundColors),
                    'borderWidth' => 0,
                ],
            ],
            'labels' => array_values($labels),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
