<?php

namespace App\Filament\Resources\UserResource\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class UserRoleChart extends ChartWidget
{
    protected static ?string $heading = 'Users by Role';
    protected static ?string $maxHeight = '300px';

    private const ROLE_COLORS = [
        User::ROLE_ADMIN       => '#ef4444',      // red
        User::ROLE_AUTHOR      => '#f59e0b',      // amber
        User::ROLE_EDITOR      => '#3b82f6',      // blue
        User::ROLE_CONTRIBUTOR => '#10b981',      // green
        User::ROLE_SUBSCRIBER  => '#8b5cf6',      // violet
        User::ROLE_USER        => '#6b7280',      // gray
    ];

    protected function getData(): array
    {
        $roleCounts = DB::table('model_has_roles')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->select('roles.name', DB::raw('count(*) as count'))
            ->groupBy('roles.name')
            ->pluck('count', 'name');

        $defaultRoles = User::defaultRoles();

        $dataset = collect($defaultRoles)->map(fn($label, $role) => [
            'count' => $roleCounts[$role] ?? 0,
            'label' => $label,
            'color' => self::ROLE_COLORS[$role] ?? '#9ca3af',
        ]);

        return [
            'datasets' => [
                [
                    'label' => __('resource.user.widgets.user_role_chart.dataset.label'),
                    'data' => $dataset->pluck('count'),
                    'backgroundColor' => $dataset->pluck('color'),
                    'borderWidth' => 0,
                ],
            ],
            'labels' => $dataset->pluck('label'),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
