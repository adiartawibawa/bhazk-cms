<?php

namespace App\Filament\Resources\ContentResource\Widgets;

use App\Models\Content;
use Filament\Widgets\ChartWidget;

class ContentStatusChart extends ChartWidget
{
    protected static ?string $heading = 'Content by Status';
    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        // Ambil data jumlah konten per status
        $statusCounts = Content::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Definisikan label dan warna berdasarkan konstanta model
        $statusConfig = [
            Content::STATUS_DRAFT => [
                'label' => 'Draft',
                'color' => '#f59e0b',
            ],
            Content::STATUS_PUBLISHED => [
                'label' => 'Published',
                'color' => '#10b981',
            ],
            Content::STATUS_ARCHIVED => [
                'label' => 'Archived',
                'color' => '#6b7280',
            ],
        ];

        // Mapping data chart
        $labels = [];
        $data = [];
        $backgroundColors = [];

        foreach ($statusConfig as $status => $config) {
            $labels[] = $config['label'];
            $data[] = $statusCounts[$status] ?? 0;
            $backgroundColors[] = $config['color'];
        }

        return [
            'datasets' => [
                [
                    'label' => 'Content by Status',
                    'data' => $data,
                    'backgroundColor' => $backgroundColors,
                    'borderWidth' => 0,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
