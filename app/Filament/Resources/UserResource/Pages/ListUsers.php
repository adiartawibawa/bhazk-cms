<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Filament\Resources\UserResource\Widgets\{
    RecentLoginActivity,
    RecentUsersTable,
    UserActivityMetrics,
    UserRegistrationTrendChart,
    UserRoleChart,
    UserStatsOverview
};
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    /**
     * Actions available in the header (e.g. create user).
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    /**
     * Widgets displayed at the top of the page.
     */
    protected function getHeaderWidgets(): array
    {
        return [
            UserStatsOverview::class,
        ];
    }

    /**
     * Tabs for filtering users by activity or role.
     */
    public function getTabs(): array
    {
        $tabs = [
            'all' => Tab::make(__('resource.user.tabs.all'))
                ->icon('heroicon-o-users'),

            'never_logged_in' => Tab::make(__('resource.user.tabs.never_logged_in'))
                ->icon('heroicon-o-clock')
                ->modifyQueryUsing(fn(Builder $query) => $query->whereNull('last_login_at')),
        ];

        // Dynamically generate role-based tabs
        foreach (User::defaultRoles() as $roleKey => $roleName) {
            $tabs[$roleKey] = Tab::make(__($roleName)) // <= bisa translate dari DB atau pakai lang jika ada
                ->icon(self::getRoleIcons()[$roleKey] ?? 'heroicon-o-user')
                ->modifyQueryUsing(fn(Builder $query) => $query->role($roleKey));
        }

        return $tabs;
    }

    /**
     * Default tab when page is loaded.
     */
    public function getDefaultActiveTab(): string|int|null
    {
        return 'all';
    }

    /**
     * Widgets displayed at the bottom of the page.
     */
    protected function getFooterWidgets(): array
    {
        return [
            UserRoleChart::class,
            UserRegistrationTrendChart::class,
            UserActivityMetrics::class,
            RecentUsersTable::class,
            RecentLoginActivity::class,
        ];
    }

    /**
     * Centralized mapping for role icons.
     */
    protected static function getRoleIcons(): array
    {
        return [
            User::ROLE_ADMIN       => 'heroicon-o-shield-check',
            User::ROLE_AUTHOR      => 'heroicon-o-pencil',
            User::ROLE_EDITOR      => 'heroicon-o-document-text',
            User::ROLE_CONTRIBUTOR => 'heroicon-o-light-bulb',
            User::ROLE_SUBSCRIBER  => 'heroicon-o-heart',
            User::ROLE_USER        => 'heroicon-o-user',
        ];
    }
}
