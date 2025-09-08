<?php

namespace App\Filament\Clusters\Settings\Pages;

use App\Filament\Clusters\Settings;
use App\Models\Content;
use App\Settings\ContentSettings;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use Illuminate\Contracts\Support\Htmlable;

class ManageContent extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $settings = ContentSettings::class;

    protected static ?string $cluster = Settings::class;

    public static function getNavigationLabel(): string
    {
        return __('resource.settings.content.navigation.label');
    }

    public function getTitle(): string|Htmlable
    {
        return __('resource.settings.content.title');
    }

    public static function getNavigationGroup(): string
    {
        return __('resource.settings.content.navigation.group');
    }

    protected static ?int $navigationSort = 2;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // Front Page Settings
                Forms\Components\Section::make(__('resource.settings.content.sections.front_page.label'))
                    ->description(__('resource.settings.content.sections.front_page.description'))
                    ->icon('heroicon-o-home')
                    ->collapsible()
                    ->schema([
                        Forms\Components\Radio::make('front_page_type')
                            ->label(__('resource.settings.content.fields.front_page_type'))
                            ->options([
                                'latest_posts' => __('resource.settings.content.options.front_page_type.latest_posts'),
                                'static_page'  => __('resource.settings.content.options.front_page_type.static_page'),
                            ])
                            ->default('latest_posts')
                            ->required()
                            ->reactive()
                            ->inline()
                            ->columnSpanFull(),

                        Forms\Components\Select::make('front_page_id')
                            ->label(__('resource.settings.content.fields.front_page_id'))
                            ->options(Content::query()->pluck('title', 'id'))
                            ->searchable()
                            ->visible(fn($get) => $get('front_page_type') === 'static_page')
                            ->helperText(__('resource.settings.content.helpers.front_page_id'))
                            ->columnSpanFull(),
                    ]),

                // Content Settings
                Forms\Components\Section::make(__('resource.settings.content.sections.content.label'))
                    ->description(__('resource.settings.content.sections.content.description'))
                    ->icon('heroicon-o-document-text')
                    ->collapsible()
                    ->schema([
                        Forms\Components\TextInput::make('posts_per_page')
                            ->label(__('resource.settings.content.fields.posts_per_page'))
                            ->numeric()
                            ->minValue(1)
                            ->default(10)
                            ->required()
                            ->suffix(__('resource.settings.content.placeholders.posts_per_page'))
                            ->helperText(__('resource.settings.content.helpers.posts_per_page')),
                    ]),

                // Comment Settings
                Forms\Components\Section::make(__('resource.settings.content.sections.comments.label'))
                    ->description(__('resource.settings.content.sections.comments.description'))
                    ->icon('heroicon-o-chat-bubble-left-ellipsis')
                    ->collapsible()
                    ->schema([
                        Forms\Components\Toggle::make('comment_status')
                            ->label(__('resource.settings.content.fields.comment_status'))
                            ->default(true)
                            ->onColor('success')
                            ->offColor('danger')
                            ->inline(false),

                        Forms\Components\Toggle::make('comment_moderation')
                            ->label(__('resource.settings.content.fields.comment_moderation'))
                            ->default(true)
                            ->onColor('warning')
                            ->offColor('gray')
                            ->inline(false)
                            ->helperText(__('resource.settings.content.helpers.comment_moderation')),
                    ]),

                // Permalink Settings
                Forms\Components\Section::make(__('resource.settings.content.sections.permalinks.label'))
                    ->description(__('resource.settings.content.sections.permalinks.description'))
                    ->icon('heroicon-o-link')
                    ->collapsible()
                    ->schema([
                        Forms\Components\Select::make('permalink_structure')
                            ->label(__('resource.settings.content.fields.permalink_structure'))
                            ->options([
                                '/%postname%/' => __('resource.settings.content.options.permalink_structure.postname'),
                                '/%post_id%/'  => __('resource.settings.content.options.permalink_structure.post_id'),
                                '/%year%/%monthnum%/%postname%/' => __('resource.settings.content.options.permalink_structure.date_postname'),
                            ])
                            ->default('/%postname%/')
                            ->required()
                            ->native(false),

                        Forms\Components\Placeholder::make('permalink_preview')
                            ->label(__('resource.settings.content.fields.permalink_preview'))
                            ->content(
                                fn($get) =>
                                url('') .
                                    ($get('permalink_structure') === '/%postname%/' ? '/sample-post/' : ($get('permalink_structure') === '/%post_id%/' ? '/123/' : '/2025/08/sample-post/'))
                            )
                            ->helperText(__('resource.settings.content.helpers.permalink_preview'))
                    ]),
            ]);
    }
}
