<?php

namespace App\Filament\Resources\TicketResource\RelationManagers;

use App\Models\Ticket;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StatusHistoryRelationManager extends RelationManager
{
    protected static string $relationship = 'statusHistory';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('old_status')
                    ->options([
                        Ticket::STATUS_OPEN => 'Open',
                        Ticket::STATUS_IN_PROGRESS => 'In Progress',
                        Ticket::STATUS_ON_HOLD => 'On Hold',
                        Ticket::STATUS_RESOLVED => 'Resolved',
                        Ticket::STATUS_CLOSED => 'Closed',
                    ]),
                Forms\Components\Select::make('new_status')
                    ->options([
                        Ticket::STATUS_OPEN => 'Open',
                        Ticket::STATUS_IN_PROGRESS => 'In Progress',
                        Ticket::STATUS_ON_HOLD => 'On Hold',
                        Ticket::STATUS_RESOLVED => 'Resolved',
                        Ticket::STATUS_CLOSED => 'Closed',
                    ])
                    ->required(),
                Forms\Components\Textarea::make('change_reason')
                    ->maxLength(65535)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('new_status')
            ->columns([
                Tables\Columns\TextColumn::make('changedBy.email')
                    ->label('Changed By')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('old_status')
                    ->colors([
                        'danger' => Ticket::STATUS_OPEN,
                        'warning' => Ticket::STATUS_IN_PROGRESS,
                        'info' => Ticket::STATUS_ON_HOLD,
                        'success' => Ticket::STATUS_RESOLVED,
                        'secondary' => Ticket::STATUS_CLOSED,
                    ]),
                Tables\Columns\IconColumn::make('arrow')
                    ->icon('heroicon-o-arrow-right')
                    ->extraAttributes(['class' => 'text-gray-400']),
                Tables\Columns\BadgeColumn::make('new_status')
                    ->colors([
                        'danger' => Ticket::STATUS_OPEN,
                        'warning' => Ticket::STATUS_IN_PROGRESS,
                        'info' => Ticket::STATUS_ON_HOLD,
                        'success' => Ticket::STATUS_RESOLVED,
                        'secondary' => Ticket::STATUS_CLOSED,
                    ]),
                Tables\Columns\TextColumn::make('change_reason')
                    ->limit(50),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
