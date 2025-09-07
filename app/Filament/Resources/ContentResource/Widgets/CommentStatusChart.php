<?php

namespace App\Filament\Resources\ContentResource\Widgets;

use App\Models\ContentComment;
use Filament\Widgets\ChartWidget;

class CommentStatusChart extends ChartWidget
{
    protected static ?string $heading = 'Comments by Status';
    protected static ?string $maxHeight = '250px';

    protected function getData(): array
    {
        // Ambil count per status dari DB
        $statusCounts = ContentComment::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Centralized config (label + color)
        $statusConfig = [
            ContentComment::STATUS_PENDING  => ['label' => 'Pending',  'color' => '#f59e0b'],
            ContentComment::STATUS_APPROVED => ['label' => 'Approved', 'color' => '#10b981'],
            ContentComment::STATUS_REJECTED => ['label' => 'Rejected', 'color' => '#ef4444'],
            ContentComment::STATUS_SPAM     => ['label' => 'Spam',     'color' => '#6b7280'],
        ];

        $labels = [];
        $data = [];
        $colors = [];

        foreach ($statusConfig as $status => $config) {
            $labels[] = $config['label'];
            $data[] = $statusCounts[$status] ?? 0;
            $colors[] = $config['color'];
        }

        return [
            'datasets' => [
                [
                    'label' => 'Comments by Status',
                    'data' => $data,
                    'backgroundColor' => $colors,
                    'borderWidth' => 0,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => false, // legend bisa diaktifkan kalau perlu
                ],
            ],
            'scales' => [
                'x' => [
                    'beginAtZero' => true,
                ],
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
            ],
        ];
    }
}
