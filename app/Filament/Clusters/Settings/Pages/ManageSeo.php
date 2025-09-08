<?php

namespace App\Filament\Clusters\Settings\Pages;

use App\Filament\Clusters\Settings;
use App\Settings\SeoSettings;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use Illuminate\Contracts\Support\Htmlable;

class ManageSeo extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-globe-asia-australia';

    protected static string $settings = SeoSettings::class;

    protected static ?string $cluster = Settings::class;

    public static function getNavigationLabel(): string
    {
        return __('resource.settings.seo.navigation.label');
    }

    public function getTitle(): string|Htmlable
    {
        return __('resource.settings.seo.title');
    }

    // Grup navigasi multi-bahasa
    public static function getNavigationGroup(): string
    {
        return __('resource.settings.seo.navigation.group');
    }

    protected static ?int $navigationSort = 5;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('SeoTabs')
                    ->tabs([

                        // META SETTINGS
                        Tabs\Tab::make(__('resource.settings.seo.tabs.meta.label'))
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                Section::make(__('resource.settings.seo.sections.meta.label'))
                                    ->description(__('resource.settings.seo.sections.meta.description'))
                                    ->schema([
                                        Forms\Components\TextInput::make('meta_title')
                                            ->label(__('resource.settings.seo.fields.meta_title'))
                                            ->required()
                                            ->maxLength(60)
                                            ->helperText(__('resource.settings.seo.helpers.meta_title')),

                                        Forms\Components\Textarea::make('meta_description')
                                            ->label(__('resource.settings.seo.fields.meta_description'))
                                            ->rows(4)
                                            ->maxLength(160)
                                            ->helperText(__('resource.settings.seo.helpers.meta_description')),

                                        Forms\Components\TextInput::make('meta_keywords')
                                            ->label(__('resource.settings.seo.fields.meta_keywords'))
                                            ->placeholder(__('resource.settings.seo.placeholders.meta_keywords'))
                                            ->helperText(__('resource.settings.seo.helpers.meta_keywords')),
                                    ]),
                            ]),

                        // SITE IDENTITY
                        Tabs\Tab::make(__('resource.settings.seo.tabs.identity.label'))
                            ->icon('heroicon-o-photo')
                            ->schema([
                                Section::make(__('resource.settings.seo.sections.branding.label'))
                                    ->description(__('resource.settings.seo.sections.branding.description'))
                                    ->schema([
                                        Forms\Components\FileUpload::make('site_logo')
                                            ->label(__('resource.settings.seo.fields.site_logo'))
                                            ->image()
                                            ->directory('seo')
                                            ->imageEditor()
                                            ->helperText(__('resource.settings.seo.helpers.site_logo')),

                                        Forms\Components\FileUpload::make('site_favicon')
                                            ->label(__('resource.settings.seo.fields.site_favicon'))
                                            ->image()
                                            ->directory('seo')
                                            ->imageEditor()
                                            ->acceptedFileTypes(['image/vnd.microsoft.icon'])
                                            ->helperText(__('resource.settings.seo.helpers.site_favicon')),
                                    ]),
                            ]),

                        // ROBOTS.TXT
                        Tabs\Tab::make(__('resource.settings.seo.tabs.robots.label'))
                            ->icon('heroicon-o-cpu-chip')
                            ->schema([
                                Section::make(__('resource.settings.seo.sections.robots_config.label'))
                                    ->description(__('resource.settings.seo.sections.robots_config.description'))
                                    ->schema([
                                        Forms\Components\Textarea::make('robots_txt')
                                            ->label(__('resource.settings.seo.fields.robots_txt'))
                                            ->rows(10)
                                            ->extraInputAttributes(['style' => 'font-family: monospace;'])
                                            ->helperText(__('resource.settings.seo.helpers.robots_txt')),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull()
                    ->persistTabInQueryString(),
            ]);
    }
}
