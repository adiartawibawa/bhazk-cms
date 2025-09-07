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
use Filament\Actions;
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
}
