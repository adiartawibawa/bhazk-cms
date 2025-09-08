<?php

namespace App\Filament\Widgets;

use App\Models\ContentComment;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class RecentComments extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?string $heading = 'Recent Comments';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 4;

    protected function applyDateFilters(Builder $query, $startDate, $endDate): Builder
    {
        return $query
            ->when($startDate, fn(Builder $q) => $q->whereDate('created_at', '>=', $startDate))
            ->when($endDate, fn(Builder $q) => $q->whereDate('created_at', '<=', $endDate));
    }

    public function table(Table $table): Table
    {
        $startDate = $this->filters['startDate'] ?? null;
        $endDate   = $this->filters['endDate'] ?? null;

        return $table
            ->query(
                $this->applyDateFilters(
                    ContentComment::query()
                        ->with(['content', 'user'])
                        ->latest(),
                    $startDate,
                    $endDate
                )->take(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('user.username')
                    ->label('User')
                    ->sortable()
                    ->default('Guest')
                    ->tooltip(fn($record) => $record->user->username ?? 'Guest'),

                Tables\Columns\TextColumn::make('content.title')
                    ->label('Content')
                    ->limit(30)
                    ->sortable()
                    ->default('N/A')
                    ->tooltip(fn($record) => optional($record->content)->title ?? 'N/A'),

                Tables\Columns\TextColumn::make('comment')
                    ->label('Comment')
                    ->limit(50)
                    ->html()
                    ->tooltip(fn($record) => strip_tags($record->comment ?? '')),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => ContentComment::STATUS_PENDING,
                        'success' => ContentComment::STATUS_APPROVED,
                        'danger'  => ContentComment::STATUS_REJECTED,
                        'secondary' => ContentComment::STATUS_SPAM,
                    ])
                    ->formatStateUsing(fn($state) => ucfirst($state))
                    ->sortable(),

                Tables\Columns\TextColumn::make('likes_count')
                    ->label('Likes')
                    ->numeric()
                    ->sortable()
                    ->default(0),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Posted')
                    ->dateTime('M d, Y H:i')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('View')
                    ->icon('heroicon-o-eye')
                    ->url(fn(ContentComment $record): string => route('filament.admin.resources.content-comments.edit', $record))
                    ->openUrlInNewTab(),
            ]);
    }

    protected function isTablePaginationEnabled(): bool
    {
        return false;
    }
}
