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
                Components\Section::make(__('resource.user.view.sections.profile'))
                    ->schema([
                        SpatieMediaLibraryImageEntry::make('avatar')
                            ->collection('avatars')
                            ->circular()
                            ->size(120)
                            ->label(''),

                        Components\TextEntry::make('full_name')
                            ->label(__('resource.user.view.fields.full_name'))
                            ->getStateUsing(fn($record) => $record->getFilamentName()),

                        Components\TextEntry::make('username')
                            ->label(__('resource.user.view.fields.username')),

                        Components\TextEntry::make('email')
                            ->label(__('resource.user.view.fields.email')),
                    ])
                    ->columns(2),

                Components\Section::make(__('resource.user.view.sections.status'))
                    ->schema([
                        Components\IconEntry::make('is_active')
                            ->boolean()
                            ->label(__('resource.user.view.fields.is_active')),

                        Components\TextEntry::make('last_login_ip')
                            ->badge()
                            ->color('gray')
                            ->label(__('resource.user.view.fields.last_login_ip'))
                            ->placeholder(__('resource.user.view.placeholders.never_logged_in')),

                        Components\TextEntry::make('last_login_at')
                            ->dateTime('d M Y H:i')
                            ->label(__('resource.user.view.fields.last_login_at'))
                            ->placeholder(__('resource.user.view.placeholders.never_logged_in')),
                    ])
                    ->columns(3),

                Components\Section::make(__('resource.user.view.sections.system'))
                    ->schema([
                        Components\TextEntry::make('timezone')
                            ->label(__('resource.user.view.fields.timezone')),

                        Components\TextEntry::make('created_at')
                            ->dateTime('d M Y H:i')
                            ->label(__('resource.user.view.fields.created_at')),

                        Components\TextEntry::make('updated_at')
                            ->dateTime('d M Y H:i')
                            ->label(__('resource.user.view.fields.updated_at')),

                        Components\TextEntry::make('creator.username')
                            ->label(__('resource.user.view.fields.created_by'))
                            ->placeholder(__('resource.user.view.placeholders.empty')),

                        Components\TextEntry::make('updater.username')
                            ->label(__('resource.user.view.fields.updated_by'))
                            ->placeholder(__('resource.user.view.placeholders.empty')),
                    ])
                    ->columns(5),
            ]);
    }
}
