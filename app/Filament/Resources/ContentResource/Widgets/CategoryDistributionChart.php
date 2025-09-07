<?php

namespace App\Filament\Resources\ContentResource\Widgets;

use App\Models\Category;
use Filament\Widgets\ChartWidget;

class CategoryDistributionChart extends ChartWidget
{
    protected static ?string $heading = 'Content by Category';
    protected static ?string $maxHeight = '350px';

    protected function getData(): array
    {
        // Ambil 8 kategori teratas berdasarkan jumlah konten
        $categoryCounts = Category::withCount('contents')
            ->orderByDesc('contents_count')
            ->limit(8)
            ->pluck('contents_count', 'name')
            ->toArray();

        // Fallback kalau tidak ada kategori
        if (empty($categoryCounts)) {
            return [
                'datasets' => [
                    [
                        'label' => 'Content Count',
                        'data' => [0],
                        'backgroundColor' => ['#d1d5db'],
                    ],
                ],
                'labels' => ['No Data'],
            ];
        }

        // Warna-warna default (bisa generate random kalau lebih panjang)
        $colors = [
            '#ef4444',
            '#f59e0b',
            '#10b981',
            '#3b82f6',
            '#8b5cf6',
            '#ec4899',
            '#06b6d4',
            '#84cc16',
        ];

        return [
            'datasets' => [
                [
                    'label' => 'Content Count',
                    'data' => array_values($categoryCounts),
                    'backgroundColor' => array_slice($colors, 0, count($categoryCounts)),
                    'borderWidth' => 0,
                ],
            ],
            'labels' => array_keys($categoryCounts),
        ];
    }

    protected function getType(): string
    {
        return 'polarArea';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'right',
                ],
                'tooltip' => [
                    'callbacks' => [
                        'label' => fn($tooltipItem) => $tooltipItem['label'] . ': ' . $tooltipItem['raw'],
                    ],
                ],
            ],
        ];
    }
}
