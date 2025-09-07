<?php

namespace App\Filament\Resources\TicketResource\Widgets;

use App\Models\Ticket;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentTicketsTable extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Ticket::query()
                    ->with(['user', 'assignedTo'])
                    ->latest()
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('ticket_number')
                    ->searchable()
                    ->sortable()
                    ->label('Ticket ID'),

                Tables\Columns\TextColumn::make('user.email')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('subject')
                    ->searchable()
                    ->limit(30)
                    ->tooltip(fn($record) => $record->subject),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'danger' => Ticket::STATUS_OPEN,
                        'warning' => Ticket::STATUS_IN_PROGRESS,
                        'info' => Ticket::STATUS_ON_HOLD,
                        'success' => Ticket::STATUS_RESOLVED,
                        'secondary' => Ticket::STATUS_CLOSED,
                    ])
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('priority')
                    ->colors([
                        'gray' => Ticket::PRIORITY_LOW,
                        'success' => Ticket::PRIORITY_MEDIUM,
                        'warning' => Ticket::PRIORITY_HIGH,
                        'danger' => Ticket::PRIORITY_URGENT,
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Created'),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->url(fn(Ticket $record): string => route('filament.backend.resources.tickets.edit', $record))
                    ->icon('heroicon-o-eye'),
            ]);
    }

    protected function isTablePaginationEnabled(): bool
    {
        return false;
    }
}
