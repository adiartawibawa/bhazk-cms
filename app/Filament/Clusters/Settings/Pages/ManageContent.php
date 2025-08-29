<?php

namespace App\Filament\Clusters\Settings\Pages;

use App\Filament\Clusters\Settings;
use App\Models\Content;
use App\Settings\ContentSettings;
use App\Models\Post; // kalau kamu punya model Post
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageContent extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $settings = ContentSettings::class;

    protected static ?string $cluster = Settings::class;

    protected static ?string $navigationLabel = 'Content';

    protected static ?string $title = 'Manage Content Settings';

    protected static ?string $navigationGroup = 'Content Management';

    protected static ?int $navigationSort = 2;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // Section for Front Page Settings
                Forms\Components\Section::make('Front Page Settings')
                    ->description('Configure how your front page is displayed to visitors')
                    ->icon('heroicon-o-home')
                    ->collapsible()
                    ->schema([
                        Forms\Components\Radio::make('front_page_type')
                            ->label('Front Page Display')
                            ->options([
                                'latest_posts' => 'Your latest posts',
                                'static_page'  => 'A static page',
                            ])
                            ->default('latest_posts')
                            ->required()
                            ->reactive()
                            ->inline()
                            ->columnSpanFull(),

                        Forms\Components\Select::make('front_page_id')
                            ->label('Front Page')
                            ->options(Content::query()->pluck('title', 'id'))
                            ->searchable()
                            ->visible(fn($get) => $get('front_page_type') === 'static_page')
                            ->helperText('Select the page to use as your front page (if using static page).')
                            ->columnSpanFull(),
                    ]),

                // Section for Content Settings
                Forms\Components\Section::make('Content Settings')
                    ->description('Configure how your content is displayed and managed')
                    ->icon('heroicon-o-document-text')
                    ->collapsible()
                    ->schema([
                        Forms\Components\TextInput::make('posts_per_page')
                            ->label('Posts Per Page')
                            ->numeric()
                            ->minValue(1)
                            ->default(10)
                            ->required()
                            ->suffix('posts')
                            ->helperText('Number of posts to show on archive pages.'),
                    ]),

                // Section for Comment Settings
                Forms\Components\Section::make('Comment Settings')
                    ->description('Manage how comments work on your site')
                    ->icon('heroicon-o-chat-bubble-left-ellipsis')
                    ->collapsible()
                    ->schema([
                        Forms\Components\Toggle::make('comment_status')
                            ->label('Allow Comments')
                            ->default(true)
                            ->onColor('success')
                            ->offColor('danger')
                            ->inline(false),

                        Forms\Components\Toggle::make('comment_moderation')
                            ->label('Require Comment Moderation')
                            ->default(true)
                            ->onColor('warning')
                            ->offColor('gray')
                            ->inline(false)
                            ->helperText('All comments must be approved by an administrator before being published.'),
                    ]),

                // Section for Permalink Settings
                Forms\Components\Section::make('Permalink Settings')
                    ->description('Customize how your content URLs are structured')
                    ->icon('heroicon-o-link')
                    ->collapsible()
                    ->schema([
                        Forms\Components\Select::make('permalink_structure')
                            ->label('Permalink Structure')
                            ->options([
                                '/%postname%/' => 'Post Name (/sample-post/)',
                                '/%post_id%/'  => 'Post ID (/123/)',
                                '/%year%/%monthnum%/%postname%/' => 'Date and Name (/2025/08/sample-post/)',
                            ])
                            ->default('/%postname%/')
                            ->required()
                            ->native(false),

                        Forms\Components\Placeholder::make('permalink_preview')
                            ->label('Preview')
                            ->content(
                                fn($get) =>
                                url('') .
                                    ($get('permalink_structure') === '/%postname%/' ? '/sample-post/' : ($get('permalink_structure') === '/%post_id%/' ? '/123/' :
                                        '/2025/08/sample-post/'))
                            )
                            ->helperText('This is how your post URLs will appear.')
                    ]),
            ]);
    }
}
