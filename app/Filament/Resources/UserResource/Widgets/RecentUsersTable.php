<?php

namespace App\Filament\Resources\UserResource\Widgets;

use App\Models\User;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentUsersTable extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 2;

    /**
     * Mapping role ke warna badge.
     */
    private const ROLE_COLORS = [
        User::ROLE_ADMIN => 'danger',
        User::ROLE_AUTHOR => 'warning',
        User::ROLE_EDITOR => 'primary',
        User::ROLE_CONTRIBUTOR => 'success',
        User::ROLE_SUBSCRIBER => 'purple',
        User::ROLE_USER => 'gray',
    ];

    public function table(Table $table): Table
    {
        return $table
            ->query(
                User::query()
                    ->with(['roles'])
                    ->latest()
                    ->take(10) // gunakan take biar konsisten
            )
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('avatar')
                    ->collection('avatars')
                    ->circular()
                    ->defaultImageUrl(fn(User $record) => $record->getFilamentAvatarUrl())
                    ->label(''),

                Tables\Columns\TextColumn::make('username')
                    ->label('Username')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('full_name')
                    ->label('Full Name')
                    ->state(fn(User $record) => $record->full_name) // pakai accessor
                    ->searchable(['first_name', 'last_name']),

                Tables\Columns\BadgeColumn::make('roles.name')
                    ->label('Role')
                    ->colors(self::ROLE_COLORS)
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Registered')
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('View')
                    ->icon('heroicon-o-eye')
                    ->url(fn(User $record): string => route('filament.backend.resources.users.view', $record)),
            ]);
    }

    protected function isTablePaginationEnabled(): bool
    {
        return false;
    }
}
