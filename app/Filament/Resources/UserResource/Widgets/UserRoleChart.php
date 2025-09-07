<?php

namespace App\Filament\Resources\UserResource\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class UserRoleChart extends ChartWidget
{
    protected static ?string $heading = 'Users by Role';
    protected static ?string $maxHeight = '300px';

    /**
     * Warna default per role (hex format).
     */
    private const ROLE_COLORS = [
        User::ROLE_ADMIN => '#ef4444',      // red
        User::ROLE_AUTHOR => '#f59e0b',     // amber
        User::ROLE_EDITOR => '#3b82f6',     // blue
        User::ROLE_CONTRIBUTOR => '#10b981', // green
        User::ROLE_SUBSCRIBER => '#8b5cf6', // violet
        User::ROLE_USER => '#6b7280',       // gray
    ];

    protected function getData(): array
    {
        // Ambil jumlah user per role
        $roleCounts = DB::table('model_has_roles')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->select('roles.name', DB::raw('count(*) as count'))
            ->groupBy('roles.name')
            ->pluck('count', 'name');

        // Ambil daftar role default dari model User
        $defaultRoles = User::defaultRoles();

        // Buat dataset dinamis berdasarkan defaultRoles
        $dataset = collect($defaultRoles)->map(fn($label, $role) => [
            'count' => $roleCounts[$role] ?? 0,
            'label' => $label,
            'color' => self::ROLE_COLORS[$role] ?? '#9ca3af', // fallback gray-400
        ]);

        return [
            'datasets' => [
                [
                    'label' => 'Users by Role',
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
