<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TicketResource\Pages;
use App\Filament\Resources\TicketResource\RelationManagers;
use App\Models\Ticket;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    protected static ?string $navigationGroup = 'Support';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('ticket_number')
                    ->default('TKT-' . strtoupper(uniqid()))
                    ->required()
                    ->maxLength(255)
                    ->unique(Ticket::class, 'ticket_number', ignoreRecord: true),
                Forms\Components\Select::make('user_id')
                    ->label('Customer')
                    ->relationship('user', 'email')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('assigned_to')
                    ->label('Assigned Agent')
                    ->options(User::role(User::ROLE_ADMIN)->pluck('email', 'id'))
                    ->searchable()
                    ->preload(),
                Forms\Components\TextInput::make('subject')
                    ->required()
                    ->maxLength(255),
                Forms\Components\RichEditor::make('description')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Select::make('status')
                    ->options([
                        Ticket::STATUS_OPEN => 'Open',
                        Ticket::STATUS_IN_PROGRESS => 'In Progress',
                        Ticket::STATUS_ON_HOLD => 'On Hold',
                        Ticket::STATUS_RESOLVED => 'Resolved',
                        Ticket::STATUS_CLOSED => 'Closed',
                    ])
                    ->required()
                    ->default(Ticket::STATUS_OPEN),
                Forms\Components\Select::make('priority')
                    ->options([
                        Ticket::PRIORITY_LOW => 'Low',
                        Ticket::PRIORITY_MEDIUM => 'Medium',
                        Ticket::PRIORITY_HIGH => 'High',
                        Ticket::PRIORITY_URGENT => 'Urgent',
                    ])
                    ->required()
                    ->default(Ticket::PRIORITY_MEDIUM),
                Forms\Components\Select::make('type')
                    ->options([
                        Ticket::TYPE_BUG => 'Bug',
                        Ticket::TYPE_FEATURE_REQUEST => 'Feature Request',
                        Ticket::TYPE_SUPPORT => 'Support',
                        Ticket::TYPE_BILLING => 'Billing',
                        Ticket::TYPE_OTHER => 'Other',
                    ])
                    ->required()
                    ->default(Ticket::TYPE_SUPPORT),
                Forms\Components\Select::make('source')
                    ->options([
                        Ticket::SOURCE_WEB => 'Web',
                        Ticket::SOURCE_EMAIL => 'Email',
                        Ticket::SOURCE_PHONE => 'Phone',
                        Ticket::SOURCE_CHAT => 'Chat',
                    ])
                    ->required()
                    ->default(Ticket::SOURCE_WEB),
                Forms\Components\DateTimePicker::make('first_response_at'),
                Forms\Components\DateTimePicker::make('resolved_at'),
                Forms\Components\DateTimePicker::make('closed_at'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('ticket_number')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.email')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('assignedTo.email')
                    ->label('Assigned To')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('subject')
                    ->searchable()
                    ->limit(50),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'danger' => Ticket::STATUS_OPEN,
                        'warning' => Ticket::STATUS_IN_PROGRESS,
                        'info' => Ticket::STATUS_ON_HOLD,
                        'success' => Ticket::STATUS_RESOLVED,
                        'secondary' => Ticket::STATUS_CLOSED,
                    ]),
                Tables\Columns\BadgeColumn::make('priority')
                    ->colors([
                        'gray' => Ticket::PRIORITY_LOW,
                        'success' => Ticket::PRIORITY_MEDIUM,
                        'warning' => Ticket::PRIORITY_HIGH,
                        'danger' => Ticket::PRIORITY_URGENT,
                    ]),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        Ticket::TYPE_BUG => 'danger',
                        Ticket::TYPE_FEATURE_REQUEST => 'info',
                        Ticket::TYPE_SUPPORT => 'success',
                        Ticket::TYPE_BILLING => 'warning',
                        Ticket::TYPE_OTHER => 'gray',
                    }),
                Tables\Columns\TextColumn::make('response_count')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        Ticket::STATUS_OPEN => 'Open',
                        Ticket::STATUS_IN_PROGRESS => 'In Progress',
                        Ticket::STATUS_ON_HOLD => 'On Hold',
                        Ticket::STATUS_RESOLVED => 'Resolved',
                        Ticket::STATUS_CLOSED => 'Closed',
                    ]),
                Tables\Filters\SelectFilter::make('priority')
                    ->options([
                        Ticket::PRIORITY_LOW => 'Low',
                        Ticket::PRIORITY_MEDIUM => 'Medium',
                        Ticket::PRIORITY_HIGH => 'High',
                        Ticket::PRIORITY_URGENT => 'Urgent',
                    ]),
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        Ticket::TYPE_BUG => 'Bug',
                        Ticket::TYPE_FEATURE_REQUEST => 'Feature Request',
                        Ticket::TYPE_SUPPORT => 'Support',
                        Ticket::TYPE_BILLING => 'Billing',
                        Ticket::TYPE_OTHER => 'Other',
                    ]),
                Tables\Filters\SelectFilter::make('assigned_to')
                    ->label('Assigned Agent')
                    ->options(User::role(User::ROLE_ADMIN)->pluck('email', 'id')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\MessagesRelationManager::class,
            RelationManagers\StatusHistoryRelationManager::class,
            RelationManagers\AttachmentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTickets::route('/'),
            'create' => Pages\CreateTicket::route('/create'),
            'edit' => Pages\EditTicket::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
