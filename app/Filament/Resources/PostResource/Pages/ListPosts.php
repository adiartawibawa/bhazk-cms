<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use Filament\Actions;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\ToggleButtons;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListPosts extends ListRecords
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        $options = [];
        $icons = [];
        $colors = [];

        // if (filament('filament-cms')::$allowGitHubImport) {
        //     $options["open-source"] = trans('cms.content.posts.import.github_type');
        //     $icons["open-source"] = "heroicon-o-code-bracket-square";
        //     $colors["open-source"] = "success";
        // }
        // if (filament('filament-cms')::$allowBehanceImport) {
        //     $options["portfolio"] = trans('cms.content.posts.import.behance_type');
        //     $icons["portfolio"] = "heroicon-o-photo";
        //     $colors["portfolio"] = "warning";
        // }

        // if (config('filament-cms.youtube_key') && filament('filament-cms')::$allowYoutubeImport) {
        //     $options["video"] = trans('cms.content.posts.import.youtube_type');
        //     $icons["video"] = "heroicon-o-video-camera";
        //     $colors["video"] = "primary";
        // }

        return [
            Actions\Action::make('import_from_url')
                // ->hidden(!filament('filament-cms')::$allowUrlImport)
                ->label(trans('cms.content.posts.import.button'))
                ->icon('heroicon-o-inbox-arrow-down')
                ->form([
                    ToggleButtons::make('type')
                        ->label(trans('cms.content.posts.sections.type.columns.type'))
                        ->live()
                        ->options($options)
                        ->icons($icons)
                        ->colors($colors)
                        ->default('open-source')
                        ->inline()
                        ->columnSpanFull()
                        ->hiddenLabel()
                        ->required(),
                    KeyValue::make('urls')
                        ->required()
                        ->keyLabel(trans('cms.content.posts.import.url'))
                        ->valueLabel(trans('cms.content.posts.import.redirect_url')),
                ])
                ->action(function (array $data) {
                    // if ($data['type'] === 'open-source' && count($data['urls'])) {
                    //     foreach ($data['urls'] as $url => $redirect) {
                    //         dispatch(new GitHubMetaGetterJob(
                    //             url: $url,
                    //             redirect: $redirect,
                    //             userId: auth()->user()->id,
                    //             userType: get_class(auth()->user()),
                    //             panel: filament()->getCurrentPanel()->getId()
                    //         ));
                    //     }
                    // }

                    // if ($data['type'] === 'video' && count($data['urls'])) {
                    //     foreach ($data['urls'] as $url => $redirect) {
                    //         dispatch(new YoutubeMetaGetterJob(
                    //             url: $url,
                    //             redirect: $redirect,
                    //             userId: auth()->user()->id,
                    //             userType: get_class(auth()->user()),
                    //             panel: filament()->getCurrentPanel()->getId()
                    //         ));
                    //     }
                    // }

                    // if ($data['type'] === 'portfolio' && count($data['urls'])) {
                    //     foreach ($data['urls'] as $url => $redirect) {
                    //         dispatch(new BehanceMetaGetterJob(
                    //             url: $url,
                    //             userId: auth()->user()->id,
                    //             userType: get_class(auth()->user()),
                    //             panel: filament()->getCurrentPanel()->getId()
                    //         ));
                    //     }
                    // }

                    Notification::make()
                        ->title(trans('cms.content.posts.import.notifications.title'))
                        ->body(trans('cms.content.posts.import.notifications.description'))
                        ->success()
                        ->send();
                }),
            Actions\CreateAction::make(),
        ];
    }
}
