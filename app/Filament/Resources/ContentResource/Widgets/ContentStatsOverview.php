<?php

namespace App\Filament\Resources\ContentResource\Widgets;

use App\Models\Content;
use App\Models\ContentComment;
use App\Models\ContentLike;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class ContentStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $metrics = $this->getMetrics();

        return [
            Stat::make('Total Content', $metrics['totalContent'])
                ->description('All content items')
                ->icon('heroicon-o-document-text')
                ->color('primary')
                ->chart($this->getTrend(Content::class)),

            Stat::make('Published', $metrics['publishedContent'])
                ->description('Publicly available content')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->chart($this->getTrend(Content::class, ['status' => Content::STATUS_PUBLISHED])),

            Stat::make('Drafts', $metrics['draftContent'])
                ->description('Content in progress')
                ->icon('heroicon-o-pencil')
                ->color('warning')
                ->chart($this->getTrend(Content::class, ['status' => Content::STATUS_DRAFT])),

            Stat::make('Featured', $metrics['featuredContent'])
                ->description('Highlighted content')
                ->icon('heroicon-o-star')
                ->color('amber')
                ->chart($this->getTrend(Content::class, ['featured' => true])),

            Stat::make('Total Comments', $metrics['totalComments'])
                ->description('All user comments')
                ->icon('heroicon-o-chat-bubble-left-ellipsis')
                ->color('info')
                ->chart($this->getTrend(ContentComment::class)),

            Stat::make('Approved Comments', $metrics['approvedComments'])
                ->description('Moderated comments')
                ->icon('heroicon-o-chat-bubble-left')
                ->color('success')
                ->chart($this->getTrend(ContentComment::class, ['status' => ContentComment::STATUS_APPROVED])),

            Stat::make('Total Likes', $metrics['totalLikes'])
                ->description('Content engagements')
                ->icon('heroicon-o-heart')
                ->color('rose')
                ->chart($this->getTrend(ContentLike::class)),
        ];
    }

    /**
     * Ambil semua metric utama.
     */
    protected function getMetrics(): array
    {
        return [
            'totalContent'      => Content::count(),
            'publishedContent'  => Content::where('status', Content::STATUS_PUBLISHED)->count(),
            'draftContent'      => Content::where('status', Content::STATUS_DRAFT)->count(),
            'featuredContent'   => Content::where('featured', true)->count(),
            'totalComments'     => ContentComment::count(),
            'approvedComments'  => ContentComment::where('status', ContentComment::STATUS_APPROVED)->count(),
            'totalLikes'        => ContentLike::count(),
        ];
    }

    /**
     * Helper untuk bikin mini chart (sparkline).
     */
    protected function getTrend(string $model, array $where = []): array
    {
        $query = Trend::query(
            $model::query()->when($where, fn($q) => $q->where($where))
        )
            ->between(
                start: now()->subDays(14),
                end: now(),
            )
            ->perDay()
            ->count();

        return $query
            ->map(fn(TrendValue $value) => $value->aggregate)
            ->toArray();
    }
}
