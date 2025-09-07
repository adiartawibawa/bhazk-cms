<?php

namespace App\Filament\Resources\ContentResource\Widgets;

use App\Models\ContentComment;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentCommentsTable extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 5;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                ContentComment::query()
                    ->with(['content', 'user'])
                    ->latest()
                    ->limit(10)
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
                    ->tooltip(fn($record) => $record->content->title ?? 'N/A')
                    ->default('N/A'),

                Tables\Columns\TextColumn::make('comment')
                    ->label('Comment')
                    ->limit(50)
                    ->html()
                    ->tooltip(fn($record) => strip_tags($record->comment)),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => ContentComment::STATUS_PENDING,
                        'success' => ContentComment::STATUS_APPROVED,
                        'danger'  => ContentComment::STATUS_REJECTED,
                        'gray'    => ContentComment::STATUS_SPAM,
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
