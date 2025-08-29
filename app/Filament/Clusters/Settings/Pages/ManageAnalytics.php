<?php

namespace App\Filament\Clusters\Settings\Pages;

use App\Filament\Clusters\Settings;
use App\Settings\AnalyticsSettings;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageAnalytics extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static string $settings = AnalyticsSettings::class;

    protected static ?string $cluster = Settings::class;

    protected static ?string $navigationLabel = 'Analytics Settings';

    protected static ?string $title = 'Manage Analytics Settings';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // General Tracking IDs
                Forms\Components\Section::make('General Tracking')
                    ->description('Configure Google Analytics, Tag Manager, and Facebook Pixel tracking codes.')
                    ->icon('heroicon-o-chart-bar')
                    ->collapsible()
                    ->schema([
                        Forms\Components\TextInput::make('google_analytics_id')
                            ->label('Google Analytics ID')
                            ->placeholder('UA-XXXXXXXXX-X')
                            ->maxLength(50)->prefixIcon('heroicon-o-magnifying-glass-circle'),

                        Forms\Components\TextInput::make('google_tag_manager_id')
                            ->label('Google Tag Manager ID')
                            ->placeholder('GTM-XXXXXXX')
                            ->maxLength(50)->prefixIcon('heroicon-o-tag'),

                        Forms\Components\TextInput::make('facebook_pixel_id')
                            ->label('Facebook Pixel ID')
                            ->placeholder('123456789012345')
                            ->maxLength(50),
                    ]),

                // Social Login
                Forms\Components\Section::make('Social Login')
                    ->description('Enable or disable social media authentication options.')
                    ->icon('heroicon-o-user-group')
                    ->collapsible()
                    ->schema([
                        Forms\Components\Toggle::make('social_login_enabled')
                            ->label('Enable Social Login')
                            ->helperText('When enabled, users can log in using their social media accounts.')
                            ->default(false)
                            ->onColor('success')
                            ->offColor('danger')
                            ->inline(false),
                    ]),

                // Google Analytics API Config
                Forms\Components\Section::make('Google Analytics API Configuration')
                    ->description('Settings for Google Analytics API v4 integration.')
                    ->icon('heroicon-o-cog')
                    ->collapsible()
                    ->schema([
                        Forms\Components\TextInput::make('google_analytics_property_id')
                            ->label('Google Analytics Property ID')
                            ->placeholder('properties/123456789')
                            ->maxLength(100),

                        Forms\Components\TextInput::make('google_analytics_credentials_path')
                            ->label('Credentials JSON Path')
                            ->placeholder('storage/app/analytics/credentials.json')
                            ->helperText('Path to the JSON credentials file on the server.'),

                        Forms\Components\KeyValue::make('google_analytics_credentials')
                            ->label('Google Analytics Credentials (JSON)')
                            ->keyLabel('Key')
                            ->valueLabel('Value')
                            ->reorderable()
                            ->addButtonLabel('Add Credential')
                            ->helperText('Enter credentials manually if not using a credentials file.'),

                        Forms\Components\Fieldset::make('Cache Duration')
                            ->schema([
                                Forms\Components\TextInput::make('google_analytics_cache_duration.minutes')
                                    ->label('Cache Duration (minutes)')
                                    ->numeric()
                                    ->minValue(1)
                                    ->default(60)
                                    ->required()
                                    ->suffix('minutes')
                                    ->helperText('How long to cache analytics data before refreshing.'),
                            ])
                            ->columns(1),
                    ]),


            ]);
    }
}
