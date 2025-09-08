<?php

namespace App\Filament\Resources\UserResource\Widgets;

use App\Models\User;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class RecentLoginActivity extends BaseWidget
{
    public static function getHeading(): ?string
    {
        return __('resource.user.widgets.recent_login_activity.heading');
    }

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 5;

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getQuery())
            ->columns($this->getColumns())
            ->actions($this->getActions());
    }

    protected function getQuery(): Builder
    {
        return User::query()
            ->whereNotNull('last_login_at')
            ->with(['roles'])
            ->orderByDesc('last_login_at')
            ->limit(10);
    }

    protected function getColumns(): array
    {
        return [
            Tables\Columns\SpatieMediaLibraryImageColumn::make('avatar')
                ->collection('avatars')
                ->circular()
                ->defaultImageUrl(fn(User $user) => $user->getFilamentAvatarUrl())
                ->label(''),

            Tables\Columns\TextColumn::make('username')
                ->searchable()
                ->sortable()
                ->label(__('resource.user.widgets.recent_login_activity.columns.username')),

            Tables\Columns\TextColumn::make('email')
                ->searchable()
                ->sortable()
                ->label(__('resource.user.widgets.recent_login_activity.columns.email')),

            Tables\Columns\BadgeColumn::make('roles.name')
                ->label(__('resource.user.widgets.recent_login_activity.columns.role'))
                ->colors(self::getRoleColors()),

            Tables\Columns\TextColumn::make('last_login_at')
                ->dateTime()
                ->sortable()
                ->label(__('resource.user.widgets.recent_login_activity.columns.last_login'))
                ->description(fn(User $user) => $user->last_login_ip),

            Tables\Columns\TextColumn::make('login_activity')
                ->getStateUsing(fn(User $user) => $this->formatLoginActivity($user))
                ->label(__('resource.user.widgets.recent_login_activity.columns.activity'))
                ->badge()
                ->color(fn(string $state) => $this->getActivityColor($state)),
        ];
    }

    protected function getActions(): array
    {
        return [
            Tables\Actions\Action::make('view')
                ->url(fn(User $user): string => route('filament.backend.resources.users.view', $user))
                ->icon('heroicon-o-eye'),
        ];
    }

    protected static function getRoleColors(): array
    {
        return [
            'danger'  => User::ROLE_ADMIN,
            'warning' => User::ROLE_AUTHOR,
            'primary' => User::ROLE_EDITOR,
            'success' => User::ROLE_CONTRIBUTOR,
            'purple'  => User::ROLE_SUBSCRIBER,
            'gray'    => User::ROLE_USER,
        ];
    }

    protected function formatLoginActivity(User $user): string
    {
        return $user->last_login_at
            ? $user->last_login_at->diffForHumans()
            : __('resource.user.widgets.recent_login_activity.placeholders.never_logged_in');
    }

    protected function getActivityColor(string $state): string
    {
        return str_contains($state, 'hour')
            ? 'success'
            : (str_contains($state, 'day') ? 'warning' : 'gray');
    }

    protected function isTablePaginationEnabled(): bool
    {
        return false;
    }
}
