<?php

namespace App\Filament\Resources\ContentResource\Widgets;

use App\Models\Content;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class ContentPublishingTrendChart extends ChartWidget
{
    protected static ?string $heading = 'Content Publishing Trend (Last 30 Days)';
    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $startDate = now()->subDays(30)->startOfDay();
        $endDate = now()->endOfDay();

        // Buat array semua tanggal (biar tidak bolong)
        $dateRange = collect(
            range(0, $startDate->diffInDays($endDate))
        )->map(fn($i) => $startDate->copy()->addDays($i)->format('Y-m-d'));

        // Query created content
        $createdData = Content::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->pluck('count', 'date');

        // Query published content
        $publishedData = Content::selectRaw('DATE(published_at) as date, COUNT(*) as count')
            ->where('status', Content::STATUS_PUBLISHED)
            ->whereBetween('published_at', [$startDate, $endDate])
            ->groupBy('date')
            ->pluck('count', 'date');

        // Isi data sesuai tanggal range
        $createdCounts = $dateRange->map(fn($date) => $createdData[$date] ?? 0);
        $publishedCounts = $dateRange->map(fn($date) => $publishedData[$date] ?? 0);

        return [
            'datasets' => [
                [
                    'label' => 'Content Created',
                    'data' => $createdCounts,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.5)',
                    'borderColor' => 'rgb(59, 130, 246)',
                    'borderWidth' => 2,
                    'fill' => true,
                    'tension' => 0.4,
                ],
                [
                    'label' => 'Content Published',
                    'data' => $publishedCounts,
                    'backgroundColor' => 'rgba(16, 185, 129, 0.5)',
                    'borderColor' => 'rgb(16, 185, 129)',
                    'borderWidth' => 2,
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $dateRange->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'top',
                ],
            ],
            'scales' => [
                'x' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Date',
                    ],
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
