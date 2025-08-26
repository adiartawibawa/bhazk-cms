<?php

namespace App\Filament\Resources\TicketResource\RelationManagers;

use App\Models\TicketMessage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MessagesRelationManager extends RelationManager
{
    protected static string $relationship = 'messages';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\RichEditor::make('message')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Select::make('message_type')
                    ->options([
                        TicketMessage::MESSAGE_TYPE_USER => 'User',
                        TicketMessage::MESSAGE_TYPE_AGENT => 'Agent',
                        TicketMessage::MESSAGE_TYPE_SYSTEM => 'System',
                    ])
                    ->required()
                    ->default(TicketMessage::MESSAGE_TYPE_USER),
                Forms\Components\Toggle::make('is_internal')
                    ->default(false),
                Forms\Components\Toggle::make('is_first_response')
                    ->default(false),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('message')
            ->columns([
                Tables\Columns\TextColumn::make('user.email')
                    ->label('From')
                    ->sortable(),
                Tables\Columns\TextColumn::make('message')
                    ->limit(50)
                    ->html(),
                Tables\Columns\BadgeColumn::make('message_type')
                    ->colors([
                        'primary' => TicketMessage::MESSAGE_TYPE_USER,
                        'success' => TicketMessage::MESSAGE_TYPE_AGENT,
                        'secondary' => TicketMessage::MESSAGE_TYPE_SYSTEM,
                    ]),
                Tables\Columns\IconColumn::make('is_internal')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_first_response')
                    ->boolean(),
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
