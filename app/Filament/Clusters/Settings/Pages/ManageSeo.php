<?php

namespace App\Filament\Clusters\Settings\Pages;

use App\Filament\Clusters\Settings;
use App\Settings\SeoSettings;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageSeo extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-globe-asia-australia';

    protected static string $settings = SeoSettings::class;

    protected static ?string $cluster = Settings::class;

    protected static ?string $navigationLabel = 'Search Optimization';

    protected static ?string $title = 'Manage SEO Settings';

    protected static ?string $navigationGroup = 'Site Configuration';

    protected static ?int $navigationSort = 5;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('SeoTabs')
                    ->tabs([

                        // META SETTINGS
                        Tabs\Tab::make('Meta')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                Section::make('Meta Information')
                                    ->description('General SEO meta tags for your website')
                                    ->schema([
                                        Forms\Components\TextInput::make('meta_title')
                                            ->label('Meta Title')
                                            ->required()
                                            ->maxLength(60)
                                            ->helperText('Optimal length: 50–60 characters.'),

                                        Forms\Components\Textarea::make('meta_description')
                                            ->label('Meta Description')
                                            ->rows(4)
                                            ->maxLength(160)
                                            ->helperText('Optimal length: 150–160 characters.'),

                                        Forms\Components\TextInput::make('meta_keywords')
                                            ->label('Meta Keywords')
                                            ->placeholder('cms, content management, website')
                                            ->helperText('Separate keywords with commas.'),
                                    ]),
                            ]),

                        // SITE IDENTITY
                        Tabs\Tab::make('Site Identity')
                            ->icon('heroicon-o-photo')
                            ->schema([
                                Section::make('Branding')
                                    ->description('Logo and favicon for SEO and sharing')
                                    ->schema([
                                        Forms\Components\FileUpload::make('site_logo')
                                            ->label('Site Logo')
                                            ->image()
                                            ->directory('seo')
                                            ->imageEditor()
                                            ->helperText('Recommended: SVG or PNG, transparent background.'),

                                        Forms\Components\FileUpload::make('site_favicon')
                                            ->label('Favicon')
                                            ->image()
                                            ->directory('seo')
                                            ->imageEditor()
                                            ->acceptedFileTypes(['image/vnd.microsoft.icon'])
                                            ->helperText('Recommended: 32x32 or 64x64 ICO/PNG.'),
                                    ]),
                            ]),

                        // ROBOTS.TXT
                        Tabs\Tab::make('Robots')
                            ->icon('heroicon-o-cpu-chip')
                            ->schema([
                                Section::make('Robots.txt Configuration')
                                    ->description('Control how search engines crawl your site')
                                    ->schema([
                                        Forms\Components\Textarea::make('robots_txt')
                                            ->label('robots.txt')
                                            ->rows(10)
                                            ->extraInputAttributes(['style' => 'font-family: monospace;'])
                                            ->helperText('Define rules for search engine crawlers.'),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull()
                    ->persistTabInQueryString(),
            ]);
    }
}
