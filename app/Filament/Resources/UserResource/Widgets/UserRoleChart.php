<?php

namespace App\Filament\Resources\UserResource\Widgets;

use App\Filament\Resources\UserResource\Pages\ListUsers;
use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Illuminate\Support\Facades\DB;

class UserRoleChart extends ChartWidget
{
    use InteractsWithPageTable;

    protected static ?string $heading = 'Users by Role';
    protected static ?string $maxHeight = '300px';

    protected function getTablePage(): string
    {
        return ListUsers::class;
    }

    private const ROLE_COLORS = [
        User::ROLE_ADMIN       => '#ef4444', // red
        User::ROLE_AUTHOR      => '#f59e0b', // amber
        User::ROLE_EDITOR      => '#3b82f6', // blue
        User::ROLE_CONTRIBUTOR => '#10b981', // green
        User::ROLE_SUBSCRIBER  => '#8b5cf6', // violet
        User::ROLE_USER        => '#6b7280', // gray
    ];

    /**
     * Ambil filter rentang tanggal dari page table (default 30 hari terakhir).
     */
    private function getDateRange(): array
    {
        $filters = $this->tableFilters['date_range'] ?? [];

        $start = Carbon::parse($filters['start_date'] ?? Carbon::now()->subDays(30));
        $end   = Carbon::parse($filters['end_date'] ?? Carbon::now());

        return [$start, $end];
    }

    protected function getData(): array
    {
        $query = $this->getPageTableQuery();
        [$start, $end] = $this->getDateRange();

        $roleCounts = DB::table('model_has_roles')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->join('users', 'model_has_roles.model_id', '=', 'users.id')
            ->where('model_has_roles.model_type', User::class)
            ->whereIn('users.id', $query->pluck('users.id'))
            ->whereBetween('users.created_at', [$start, $end]) // filter date range
            ->select('roles.name', DB::raw('count(*) as count'))
            ->groupBy('roles.name')
            ->pluck('count', 'roles.name');

        $defaultRoles = User::defaultRoles();

        $dataset = collect($defaultRoles)->map(fn($label, $role) => [
            'count' => $roleCounts[$role] ?? 0,
            'label' => $label,
            'color' => self::ROLE_COLORS[$role] ?? '#9ca3af',
        ]);

        return [
            'datasets' => [
                [
                    'label'           => __('resource.user.widgets.user_role_chart.dataset.label'),
                    'data'            => $dataset->pluck('count'),
                    'backgroundColor' => $dataset->pluck('color'),
                    'borderWidth'     => 0,
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
