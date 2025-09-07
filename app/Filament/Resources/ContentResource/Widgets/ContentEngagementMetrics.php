<?php

namespace App\Filament\Resources\ContentResource\Widgets;

use App\Models\ContentMetric;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class ContentEngagementMetrics extends ChartWidget
{
    protected static ?string $heading = 'Engagement Trends';
    protected static ?string $maxHeight = '350px';
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        // Views trend (weekly)
        $viewsTrend = Trend::model(ContentMetric::class)
            ->between(
                start: now()->subMonths(3),
                end: now(),
            )
            ->perWeek()
            ->sum('views_count');

        // Likes trend
        $likesTrend = Trend::model(ContentMetric::class)
            ->between(
                start: now()->subMonths(3),
                end: now(),
            )
            ->perWeek()
            ->sum('likes_count');

        // Comments trend
        $commentsTrend = Trend::model(ContentMetric::class)
            ->between(
                start: now()->subMonths(3),
                end: now(),
            )
            ->perWeek()
            ->sum('comments_count');

        return [
            'datasets' => [
                [
                    'label' => 'Views',
                    'data' => $viewsTrend->map(fn(TrendValue $value) => $value->aggregate),
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59,130,246,0.3)',
                ],
                [
                    'label' => 'Likes',
                    'data' => $likesTrend->map(fn(TrendValue $value) => $value->aggregate),
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'rgba(16,185,129,0.3)',
                ],
                [
                    'label' => 'Comments',
                    'data' => $commentsTrend->map(fn(TrendValue $value) => $value->aggregate),
                    'borderColor' => '#f59e0b',
                    'backgroundColor' => 'rgba(245,158,11,0.3)',
                ],
            ],
            'labels' => $viewsTrend->map(fn(TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
