<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class Settings extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-adjustments-horizontal';

    public static function getNavigationLabel(): string
    {
        return __('resource.settings.navigation.label');
    }

    protected static ?string $slug = 'settings';

    public static function getNavigationGroup(): ?string
    {
        return __('resource.settings.navigation.group');
    }

    protected static ?int $navigationSort = 5;
}
