<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class Settings extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-adjustments-horizontal';

    protected static ?string $navigationLabel = 'Settings';

    protected static ?string $slug = 'settings';

    protected static ?string $navigationGroup = 'System';

    protected static ?int $navigationSort = 1;
}
