<?php

namespace App\Filament\Clusters\Settings\Pages;

use App\Filament\Clusters\Settings;
use App\Settings\AppearanceSettings;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\ViewField;
use Illuminate\Contracts\Support\Htmlable;

class ManageAppearance extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-swatch';

    protected static string $settings = AppearanceSettings::class;

    protected static ?string $cluster = Settings::class;

    public static function getNavigationLabel(): string
    {
        return __('resource.settings.appearance.navigation.label');
    }

    public function getTitle(): string|Htmlable
    {
        return __('resource.settings.appearance.title');
    }

    public static function getNavigationGroup(): string
    {
        return __('resource.settings.appearance.navigation.group');
    }

    protected static ?int $navigationSort = 3;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('AppearanceTabs')
                    ->tabs([
                        Tabs\Tab::make(__('resource.settings.appearance.tabs.theme.label'))
                            ->icon('heroicon-o-swatch')
                            ->schema([
                                Section::make(__('resource.settings.appearance.sections.theme.label'))
                                    ->description(__('resource.settings.appearance.sections.theme.description'))
                                    ->schema([
                                        Forms\Components\Select::make('active_theme')
                                            ->label(__('resource.settings.appearance.fields.active_theme'))
                                            ->options([
                                                'default' => __('resource.settings.appearance.options.themes.default'),
                                                'dark'    => __('resource.settings.appearance.options.themes.dark'),
                                                'light'   => __('resource.settings.appearance.options.themes.light'),
                                                'custom'  => __('resource.settings.appearance.options.themes.custom'),
                                            ])
                                            ->required()
                                            ->default('default')
                                            ->reactive()
                                            ->helperText(__('resource.settings.appearance.helpers.active_theme')),

                                        Forms\Components\Grid::make(2)
                                            ->schema([
                                                Forms\Components\ColorPicker::make('primary_color')
                                                    ->label(__('resource.settings.appearance.fields.primary_color'))
                                                    ->default('#3b82f6')
                                                    ->helperText(__('resource.settings.appearance.helpers.primary_color')),

                                                Forms\Components\ColorPicker::make('secondary_color')
                                                    ->label(__('resource.settings.appearance.fields.secondary_color'))
                                                    ->default('#64748b')
                                                    ->helperText(__('resource.settings.appearance.helpers.secondary_color')),
                                            ])
                                            ->visible(fn($get) => $get('active_theme') === 'custom'),
                                    ]),
                            ]),

                        Tabs\Tab::make(__('resource.settings.appearance.tabs.custom_code.label'))
                            ->icon('heroicon-o-code-bracket')
                            ->schema([
                                Section::make(__('resource.settings.appearance.sections.custom_code.label'))
                                    ->description(__('resource.settings.appearance.sections.custom_code.description'))
                                    ->schema([
                                        Forms\Components\Textarea::make('custom_css')
                                            ->label(__('resource.settings.appearance.fields.custom_css'))
                                            ->rows(10)
                                            ->helperText(__('resource.settings.appearance.helpers.custom_css'))
                                            ->placeholder(__('resource.settings.appearance.placeholders.custom_css'))
                                            ->extraInputAttributes(['style' => 'font-family: monospace;']),

                                        Forms\Components\Textarea::make('custom_js')
                                            ->label(__('resource.settings.appearance.fields.custom_js'))
                                            ->rows(10)
                                            ->helperText(__('resource.settings.appearance.helpers.custom_js'))
                                            ->placeholder(__('resource.settings.appearance.placeholders.custom_js'))
                                            ->extraInputAttributes(['style' => 'font-family: monospace;']),
                                    ]),
                            ]),

                        Tabs\Tab::make(__('resource.settings.appearance.tabs.preview.label'))
                            ->icon('heroicon-o-eye')
                            ->schema([
                                Section::make(__('resource.settings.appearance.sections.preview.label'))
                                    ->description(__('resource.settings.appearance.sections.preview.description'))
                                    ->schema([
                                        // Placeholder untuk theme preview jika dibutuhkan
                                        // ViewField::make('theme_preview')
                                        //     ->view('filament.settings.theme-preview')
                                        //     ->label('')
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull()
                    ->persistTabInQueryString(),
            ]);
    }
}
