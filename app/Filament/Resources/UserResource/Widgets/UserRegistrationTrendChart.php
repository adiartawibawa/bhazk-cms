<?php

namespace App\Filament\Resources\UserResource\Widgets;

use App\Filament\Resources\UserResource\Pages\ListUsers;
use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class UserRegistrationTrendChart extends ChartWidget
{
    use InteractsWithPageTable;

    protected static ?string $heading = 'User Registration Trends';
    protected static ?string $maxHeight = '300px';

    public ?string $filter = '30d';

    protected function getTablePage(): string
    {
        return ListUsers::class;
    }

    protected function getFilters(): ?array
    {
        return [
            '7d'     => __('resource.user.widgets.user_registration_trend.filters.7d'),
            '30d'    => __('resource.user.widgets.user_registration_trend.filters.30d'),
            '90d'    => __('resource.user.widgets.user_registration_trend.filters.90d'),
            'custom' => __('resource.user.widgets.user_registration_trend.filters.custom'),
        ];
    }

    private function getTableDateRange(): array
    {
        $filters = $this->tableFilters['date_range'] ?? [];

        $start = Carbon::parse($filters['start_date'] ?? now()->subDays(30));
        $end   = Carbon::parse($filters['end_date'] ?? now());

        return [$start, $end];
    }

    private function syncFilterWithTable(): void
    {
        [$start, $end] = $this->getTableDateRange();

        $days = $start->diffInDays($end) + 1;

        if ($days <= 7) {
            $this->filter = '7d';
        } elseif ($days <= 30) {
            $this->filter = '30d';
        } elseif ($days <= 90) {
            $this->filter = '90d';
        } else {
            $this->filter = 'custom';
        }
    }

    protected function getData(): array
    {
        // Sync filter dengan table date_range kalau bukan pilihan manual
        $this->syncFilterWithTable();

        [$start, $end] = $this->getTableDateRange();

        // Override sesuai pilihan filter
        switch ($this->filter) {
            case '7d':
                $start = now()->subDays(6);
                $end   = now();
                break;
            case '30d':
                $start = now()->subDays(29);
                $end   = now();
                break;
            case '90d':
                $start = now()->subDays(89);
                $end   = now();
                break;
            case 'custom':
                // tetap pakai date_range dari tabel
                break;
        }

        $query = $this->getPageTableQuery();

        $trend = Trend::query(
            $query->whereBetween('created_at', [$start, $end])->reorder()
        )
            ->between($start, $end)
            ->perDay()
            ->count();

        return [
            'datasets' => [
                [
                    'label'           => __('resource.user.widgets.user_registration_trend.dataset.label'),
                    'data'            => $trend->map(fn(TrendValue $value) => $value->aggregate),
                    'backgroundColor' => 'rgba(59, 130, 246, 0.2)',
                    'borderColor'     => 'rgb(59, 130, 246)',
                    'borderWidth'     => 2,
                    'fill'            => true,
                    'tension'         => 0.4,
                ],
            ],
            'labels' => $trend->map(
                fn(TrendValue $value) => Carbon::parse($value->date)->format('d M')
            ),
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
                'legend' => ['display' => false],
                'tooltip' => ['mode' => 'index', 'intersect' => false],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => ['precision' => 0],
                ],
                'x' => ['grid' => ['display' => false]],
            ],
            'maintainAspectRatio' => false,
            'responsive' => true,
        ];
    }
}
