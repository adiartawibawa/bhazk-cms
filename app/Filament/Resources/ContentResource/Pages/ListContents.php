<?php

namespace App\Filament\Resources\ContentResource\Pages;

use App\Filament\Resources\ContentResource;
use App\Filament\Resources\ContentResource\Widgets\CategoryDistributionChart;
use App\Filament\Resources\ContentResource\Widgets\CommentStatusChart;
use App\Filament\Resources\ContentResource\Widgets\ContentEngagementMetrics;
use App\Filament\Resources\ContentResource\Widgets\ContentPublishingTrendChart;
use App\Filament\Resources\ContentResource\Widgets\ContentStatsOverview;
use App\Filament\Resources\ContentResource\Widgets\ContentStatusChart;
use App\Filament\Resources\ContentResource\Widgets\ContentTypeDistributionChart;
use App\Filament\Resources\ContentResource\Widgets\RecentCommentsTable;
use App\Filament\Resources\ContentResource\Widgets\RecentContentTable;
use App\Models\Content;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Concerns\Translatable;

class ListContents extends ListRecords
{
    use Translatable;

    protected static string $resource = ContentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            ContentStatsOverview::class,
            ContentEngagementMetrics::class,
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            RecentContentTable::class,
            ContentStatusChart::class,
            CommentStatusChart::class,
            CategoryDistributionChart::class,
            ContentPublishingTrendChart::class,
            ContentTypeDistributionChart::class,
            RecentCommentsTable::class,
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make()
                ->label(__('resource.content.tabs.all'))
                ->icon('heroicon-o-rectangle-stack'),

            'draft' => Tab::make()
                ->label(__('resource.content.tabs.draft'))
                ->modifyQueryUsing(fn($query) => $query->where('status', Content::STATUS_DRAFT))
                ->icon('heroicon-o-pencil'),

            'published' => Tab::make()
                ->label(__('resource.content.tabs.published'))
                ->modifyQueryUsing(fn($query) => $query->where('status', Content::STATUS_PUBLISHED))
                ->icon('heroicon-o-check-circle'),

            'archived' => Tab::make()
                ->label(__('resource.content.tabs.archived'))
                ->modifyQueryUsing(fn($query) => $query->where('status', Content::STATUS_ARCHIVED))
                ->icon('heroicon-o-archive-box'),

            'featured' => Tab::make()
                ->label(__('resource.content.tabs.featured'))
                ->modifyQueryUsing(fn($query) => $query->where('featured', true))
                ->icon('heroicon-o-star'),

            'commentable' => Tab::make()
                ->label(__('resource.content.tabs.commentable'))
                ->modifyQueryUsing(fn($query) => $query->where('commentable', true))
                ->icon('heroicon-o-chat-bubble-left-ellipsis'),

            'needs_review' => Tab::make()
                ->label(__('resource.content.tabs.needs_review'))
                ->modifyQueryUsing(fn($query) => $query->where('status', Content::STATUS_DRAFT))
                ->icon('heroicon-o-exclamation-circle'),
        ];
    }
}
