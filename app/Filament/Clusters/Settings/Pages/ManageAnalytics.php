<?php

namespace App\Filament\Clusters\Settings\Pages;

use App\Filament\Clusters\Settings;
use App\Settings\AnalyticsSettings;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use Illuminate\Contracts\Support\Htmlable;

class ManageAnalytics extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static string $settings = AnalyticsSettings::class;

    protected static ?string $cluster = Settings::class;

    public static function getNavigationLabel(): string
    {
        return __('resource.settings.analytics.navigation.label');
    }

    public function getTitle(): string|Htmlable
    {
        return __('resource.settings.analytics.title');
    }

    public static function getNavigationGroup(): string
    {
        return __('resource.settings.analytics.navigation.group');
    }

    protected static ?int $navigationSort = 2;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // General Tracking IDs
                Forms\Components\Section::make(__('resource.settings.analytics.sections.general_tracking.label'))
                    ->description(__('resource.settings.analytics.sections.general_tracking.description'))
                    ->icon('heroicon-o-chart-bar')
                    ->collapsible()
                    ->schema([
                        Forms\Components\TextInput::make('google_analytics_id')
                            ->label(__('resource.settings.analytics.fields.google_analytics_id'))
                            ->placeholder(__('resource.settings.analytics.placeholders.google_analytics_id'))
                            ->maxLength(50)
                            ->prefixIcon('heroicon-o-magnifying-glass-circle'),

                        Forms\Components\TextInput::make('google_tag_manager_id')
                            ->label(__('resource.settings.analytics.fields.google_tag_manager_id'))
                            ->placeholder(__('resource.settings.analytics.placeholders.google_tag_manager_id'))
                            ->maxLength(50)
                            ->prefixIcon('heroicon-o-tag'),

                        Forms\Components\TextInput::make('facebook_pixel_id')
                            ->label(__('resource.settings.analytics.fields.facebook_pixel_id'))
                            ->placeholder(__('resource.settings.analytics.placeholders.facebook_pixel_id'))
                            ->maxLength(50),
                    ]),

                // Social Login
                Forms\Components\Section::make(__('resource.settings.analytics.sections.social_login.label'))
                    ->description(__('resource.settings.analytics.sections.social_login.description'))
                    ->icon('heroicon-o-user-group')
                    ->collapsible()
                    ->schema([
                        Forms\Components\Toggle::make('social_login_enabled')
                            ->label(__('resource.settings.analytics.fields.social_login_enabled'))
                            ->helperText(__('resource.settings.analytics.helpers.social_login_enabled'))
                            ->default(false)
                            ->onColor('success')
                            ->offColor('danger')
                            ->inline(false),
                    ]),

                // Google Analytics API Config
                Forms\Components\Section::make(__('resource.settings.analytics.sections.google_api.label'))
                    ->description(__('resource.settings.analytics.sections.google_api.description'))
                    ->icon('heroicon-o-cog')
                    ->collapsible()
                    ->schema([
                        Forms\Components\TextInput::make('google_analytics_property_id')
                            ->label(__('resource.settings.analytics.fields.google_analytics_property_id'))
                            ->placeholder(__('resource.settings.analytics.placeholders.google_analytics_property_id'))
                            ->maxLength(100),

                        Forms\Components\TextInput::make('google_analytics_credentials_path')
                            ->label(__('resource.settings.analytics.fields.google_analytics_credentials_path'))
                            ->placeholder(__('resource.settings.analytics.placeholders.google_analytics_credentials_path'))
                            ->helperText(__('resource.settings.analytics.helpers.google_analytics_credentials_path')),

                        Forms\Components\KeyValue::make('google_analytics_credentials')
                            ->label(__('resource.settings.analytics.fields.google_analytics_credentials'))
                            ->keyLabel(__('resource.settings.analytics.fields.key'))
                            ->valueLabel(__('resource.settings.analytics.fields.value'))
                            ->reorderable()
                            ->addButtonLabel(__('resource.settings.analytics.fields.add_credential'))
                            ->helperText(__('resource.settings.analytics.helpers.google_analytics_credentials')),

                        Forms\Components\Fieldset::make(__('resource.settings.analytics.fields.google_analytics_cache_duration'))
                            ->schema([
                                Forms\Components\TextInput::make('google_analytics_cache_duration.minutes')
                                    ->label(__('resource.settings.analytics.fields.google_analytics_cache_duration'))
                                    ->numeric()
                                    ->minValue(1)
                                    ->default(60)
                                    ->required()
                                    ->suffix(__('resource.settings.analytics.fields.minutes'))
                                    ->helperText(__('resource.settings.analytics.helpers.google_analytics_cache_duration')),
                            ])
                            ->columns(1),
                    ]),
            ]);
    }
}
