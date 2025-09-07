<?php

namespace App\Filament\Resources\ContentResource\Widgets;

use App\Models\Content;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentContentTable extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';
    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Content::query()
                    ->with(['contentType', 'author'])
                    ->latest()
                    ->limit(10)
            )
            ->columns([
                // Title with tooltip
                Tables\Columns\TextColumn::make('title')
                    ->label('Title')
                    ->limit(50)
                    ->tooltip(fn(Content $record) => $record->title)
                    ->searchable(),

                // Content type badge
                Tables\Columns\TextColumn::make('contentType.name')
                    ->label('Type')
                    ->badge()
                    ->sortable()
                    ->color('gray'),

                // Author username
                Tables\Columns\TextColumn::make('author.username')
                    ->label('Author')
                    ->sortable()
                    ->searchable(),

                // Status badge with dynamic colors
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->sortable()
                    ->colors([
                        'warning' => Content::STATUS_DRAFT,
                        'success' => Content::STATUS_PUBLISHED,
                        'gray'    => Content::STATUS_ARCHIVED,
                    ])
                    ->formatStateUsing(fn(string $state): string => ucfirst($state)),

                // Featured flag
                Tables\Columns\IconColumn::make('featured')
                    ->boolean()
                    ->label('Featured')
                    ->sortable(),

                // Commentable flag
                Tables\Columns\IconColumn::make('commentable')
                    ->boolean()
                    ->label('Comments')
                    ->sortable(),

                // Published date with relative time
                Tables\Columns\TextColumn::make('published_at')
                    ->label('Published')
                    ->dateTime()
                    ->description(fn(Content $record) => $record->published_at?->diffForHumans() ?? 'Not published')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('edit')
                    ->url(fn(Content $record): string => route('filament.admin.resources.contents.edit', $record))
                    ->icon('heroicon-o-pencil-square'),
            ]);
    }

    protected function isTablePaginationEnabled(): bool
    {
        return false;
    }
}
