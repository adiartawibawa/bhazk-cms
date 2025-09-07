<?php

namespace App\Filament\Resources\TicketResource\Widgets;

use App\Models\Ticket;
use Filament\Widgets\ChartWidget;

class TicketPriorityChart extends ChartWidget
{
    protected static ?string $heading = 'Tickets by Priority';
    protected static ?string $maxHeight = '250px';

    protected function getData(): array
    {
        $priorityCounts = Ticket::selectRaw('priority, count(*) as count')
            ->groupBy('priority')
            ->pluck('count', 'priority')
            ->toArray();

        $labels = [
            Ticket::PRIORITY_LOW => 'Low',
            Ticket::PRIORITY_MEDIUM => 'Medium',
            Ticket::PRIORITY_HIGH => 'High',
            Ticket::PRIORITY_URGENT => 'Urgent',
        ];

        $data = [];
        $backgroundColors = [
            Ticket::PRIORITY_LOW => '#6b7280',
            Ticket::PRIORITY_MEDIUM => '#10b981',
            Ticket::PRIORITY_HIGH => '#f59e0b',
            Ticket::PRIORITY_URGENT => '#ef4444',
        ];

        foreach ($labels as $key => $label) {
            $data[] = $priorityCounts[$key] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Tickets by Priority',
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
        return 'bar';
    }
}
