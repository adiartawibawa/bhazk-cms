<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Components\Section::make('Profile')
                    ->schema([
                        SpatieMediaLibraryImageEntry::make('avatar')
                            ->collection('avatars')
                            ->circular()
                            ->size(120)
                            ->label(''),

                        Components\TextEntry::make('full_name')
                            ->label('Full Name')
                            ->getStateUsing(fn($record) => $record->getFilamentName()),

                        Components\TextEntry::make('username')
                            ->label('Username'),

                        Components\TextEntry::make('email')
                            ->label('Email'),
                    ])
                    ->columns(2),

                Components\Section::make('Account Status')
                    ->schema([
                        Components\IconEntry::make('is_active')
                            ->boolean()
                            ->label('Is Active'),

                        Components\TextEntry::make('last_login_ip')
                            ->badge()
                            ->color('gray')
                            ->label('Last Login IP')
                            ->placeholder('Never logged in'),

                        Components\TextEntry::make('last_login_at')
                            ->dateTime('d M Y H:i')
                            ->label('Last Login')
                            ->placeholder('Never logged in'),
                    ])
                    ->columns(3),

                Components\Section::make('System Information')
                    ->schema([
                        Components\TextEntry::make('timezone')
                            ->label('Timezone'),

                        Components\TextEntry::make('created_at')
                            ->dateTime('d M Y H:i')
                            ->label('Created At'),

                        Components\TextEntry::make('updated_at')
                            ->dateTime('d M Y H:i')
                            ->label('Updated At'),

                        Components\TextEntry::make('creator.username')
                            ->label('Created By')
                            ->placeholder('-'),

                        Components\TextEntry::make('updater.username')
                            ->label('Updated By')
                            ->placeholder('-'),
                    ])
                    ->columns(5),
            ]);
    }
}
