<?php

namespace App\Filament\Resources\ContentResource\Widgets;

use App\Models\ContentType;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class ContentTypeDistributionChart extends ChartWidget
{
    protected static ?string $heading = 'Content by Type';
    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $typeCounts = DB::table('contents')
            ->join('content_types', 'contents.content_type_id', '=', 'content_types.id')
            ->select('content_types.name', DB::raw('count(*) as count'))
            ->groupBy('content_types.name')
            ->orderByDesc('count') // tampilkan yang paling banyak dulu
            ->pluck('count', 'name')
            ->toArray();

        // Handle kalau tidak ada data sama sekali
        if (empty($typeCounts)) {
            return [
                'datasets' => [[
                    'label' => 'Content Count',
                    'data' => [0],
                    'backgroundColor' => ['#d1d5db'], // abu-abu default
                    'borderWidth' => 0,
                ]],
                'labels' => ['No Data'],
            ];
        }

        // Palet warna (dinamis & bisa ditambah sesuai kebutuhan)
        $colors = [
            '#3b82f6',
            '#ef4444',
            '#10b981',
            '#f59e0b',
            '#8b5cf6',
            '#ec4899',
            '#06b6d4',
            '#84cc16',
            '#f97316',
            '#14b8a6',
            '#a855f7',
            '#6366f1',
        ];

        return [
            'datasets' => [[
                'label' => 'Content Count',
                'data' => array_values($typeCounts),
                'backgroundColor' => array_slice($colors, 0, count($typeCounts)),
                'borderWidth' => 0,
            ]],
            'labels' => array_keys($typeCounts),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
