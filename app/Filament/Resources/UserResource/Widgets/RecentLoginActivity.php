<?php

namespace App\Filament\Resources\UserResource\Widgets;

use App\Models\User;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class RecentLoginActivity extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';
    protected static ?int $sort = 5;

    /**
     * Build the table.
     */
    public function table(Table $table): Table
    {
        return $table
            ->query($this->getQuery())
            ->columns($this->getColumns())
            ->actions($this->getActions());
    }

    /**
     * Query for recent login activity.
     */
    protected function getQuery(): Builder
    {
        return User::query()
            ->whereNotNull('last_login_at')
            ->with(['roles'])
            ->orderByDesc('last_login_at')
            ->limit(10);
    }

    /**
     * Define table columns.
     */
    protected function getColumns(): array
    {
        return [
            // Avatar column
            Tables\Columns\SpatieMediaLibraryImageColumn::make('avatar')
                ->collection('avatars')
                ->circular()
                ->defaultImageUrl(fn(User $user) => $user->getFilamentAvatarUrl())
                ->label(''),

            // Username column
            Tables\Columns\TextColumn::make('username')
                ->searchable()
                ->sortable()
                ->label('Username'),

            // Email column
            Tables\Columns\TextColumn::make('email')
                ->searchable()
                ->sortable()
                ->label('Email'),

            // Roles column
            Tables\Columns\BadgeColumn::make('roles.name')
                ->label('Role')
                ->colors(self::getRoleColors()),

            // Last login column
            Tables\Columns\TextColumn::make('last_login_at')
                ->dateTime()
                ->sortable()
                ->label('Last Login')
                ->description(fn(User $user) => $user->last_login_ip),

            // Activity column
            Tables\Columns\TextColumn::make('login_activity')
                ->getStateUsing(fn(User $user) => $this->formatLoginActivity($user))
                ->label('Activity')
                ->badge()
                ->color(fn(string $state) => $this->getActivityColor($state)),
        ];
    }

    /**
     * Define table actions.
     */
    protected function getActions(): array
    {
        return [
            Tables\Actions\Action::make('view')
                ->url(fn(User $user): string => route('filament.backend.resources.users.view', $user))
                ->icon('heroicon-o-eye'),
        ];
    }

    /**
     * Define role badge colors.
     */
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

    /**
     * Format login activity text.
     */
    protected function formatLoginActivity(User $user): string
    {
        return $user->last_login_at
            ? $user->last_login_at->diffForHumans()
            : 'Never logged in';
    }

    /**
     * Decide badge color based on activity freshness.
     */
    protected function getActivityColor(string $state): string
    {
        return str_contains($state, 'hour')
            ? 'success'
            : (str_contains($state, 'day') ? 'warning' : 'gray');
    }

    /**
     * Disable pagination (we only need top 10).
     */
    protected function isTablePaginationEnabled(): bool
    {
        return false;
    }
}
