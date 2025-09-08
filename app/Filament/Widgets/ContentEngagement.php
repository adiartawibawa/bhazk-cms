<?php

namespace App\Filament\Widgets;

use App\Models\ContentMetric;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class ContentEngagement extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static ?string $heading = 'Engagement Trends of Contents';

    protected static ?string $maxHeight = '350px';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 2;

    protected function applyDateFilters(Builder $query, $startDate, $endDate): Builder
    {
        return $query
            ->when($startDate, fn(Builder $q) => $q->whereDate('created_at', '>=', $startDate))
            ->when($endDate, fn(Builder $q) => $q->whereDate('created_at', '<=', $endDate));
    }

    protected function getData(): array
    {
        // Ambil filter tanggal dari page
        $startDate = isset($this->filters['startDate'])
            ? Carbon::parse($this->filters['startDate'])
            : now()->subMonths(3);
        $endDate = isset($this->filters['endDate'])
            ? Carbon::parse($this->filters['endDate'])
            : now();

        // Views trend
        $viewsTrend = Trend::query(
            $this->applyDateFilters(ContentMetric::query(), $startDate, $endDate)
        )
            ->between($startDate, $endDate)
            ->perWeek()
            ->sum('views_count');

        // Likes trend
        $likesTrend = Trend::query(
            $this->applyDateFilters(ContentMetric::query(), $startDate, $endDate)
        )
            ->between($startDate, $endDate)
            ->perWeek()
            ->sum('likes_count');

        // Comments trend
        $commentsTrend = Trend::query(
            $this->applyDateFilters(ContentMetric::query(), $startDate, $endDate)
        )
            ->between($startDate, $endDate)
            ->perWeek()
            ->sum('comments_count');

        return [
            'datasets' => [
                [
                    'label' => 'Views',
                    'data' => $viewsTrend->map(fn(TrendValue $value) => $value->aggregate),
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59,130,246,0.3)',
                    'tension' => 0.4,
                ],
                [
                    'label' => 'Likes',
                    'data' => $likesTrend->map(fn(TrendValue $value) => $value->aggregate),
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'rgba(16,185,129,0.3)',
                    'tension' => 0.4,
                ],
                [
                    'label' => 'Comments',
                    'data' => $commentsTrend->map(fn(TrendValue $value) => $value->aggregate),
                    'borderColor' => '#f59e0b',
                    'backgroundColor' => 'rgba(245,158,11,0.3)',
                    'tension' => 0.4,
                ],
            ],
            'labels' => $viewsTrend->map(function (TrendValue $value) {
                // Kalau format "YYYY-WW"
                if (preg_match('/^\d{4}-\d{2}$/', $value->date)) {
                    [$year, $week] = explode('-', $value->date);

                    return Carbon::now()
                        ->setISODate((int) $year, (int) $week)
                        ->startOfWeek()
                        ->format('d M Y'); // contoh: "04 Aug 2025"
                }

                // Kalau format full tanggal "Y-m-d"
                return Carbon::parse($value->date)->format('d M Y');
            }),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
