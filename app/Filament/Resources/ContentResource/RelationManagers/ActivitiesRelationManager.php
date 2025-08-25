<?php

namespace App\Filament\Resources\ContentResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ActivitiesRelationManager extends RelationManager
{
    protected static string $relationship = 'activities';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return 'Activity Log';
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('event')
                    ->badge()
                    ->colors([
                        'primary' => 'created',
                        'success' => 'updated',
                        'danger' => 'deleted',
                        'warning' => 'restored',
                    ]),
                Tables\Columns\TextColumn::make('description'),
                Tables\Columns\TextColumn::make('causer.name')
                    ->label('Performed By'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('event')
                    ->options([
                        'created' => 'Created',
                        'updated' => 'Updated',
                        'deleted' => 'Deleted',
                        'restored' => 'Restored',
                    ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
