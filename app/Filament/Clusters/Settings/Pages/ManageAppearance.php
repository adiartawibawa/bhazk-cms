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

class ManageAppearance extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-swatch';

    protected static string $settings = AppearanceSettings::class;

    protected static ?string $cluster = Settings::class;

    protected static ?string $navigationLabel = 'Appearance Settings';

    protected static ?string $title = 'Manage Appearance Settings';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('AppearanceTabs')
                    ->tabs([
                        Tabs\Tab::make('Theme Selection')
                            ->icon('heroicon-o-swatch')
                            ->schema([
                                Section::make('Theme Configuration')
                                    ->description('Select and customize the visual theme for your application')
                                    ->schema([
                                        Forms\Components\Select::make('active_theme')
                                            ->label('Active Theme')
                                            ->options([
                                                'default' => 'Default Theme',
                                                'dark'    => 'Dark Theme',
                                                'light'   => 'Light Theme',
                                                'custom'  => 'Custom Theme',
                                            ])
                                            ->required()
                                            ->default('default')
                                            ->reactive()
                                            ->helperText('Choose the main theme that will be used throughout the application.'),

                                        Forms\Components\Grid::make(2)
                                            ->schema([
                                                Forms\Components\ColorPicker::make('primary_color')
                                                    ->label('Primary Color')
                                                    ->default('#3b82f6')
                                                    ->helperText('Main brand color used for buttons and links.'),

                                                Forms\Components\ColorPicker::make('secondary_color')
                                                    ->label('Secondary Color')
                                                    ->default('#64748b')
                                                    ->helperText('Secondary color used for accents and highlights.'),
                                            ])
                                            ->visible(fn($get) => $get('active_theme') === 'custom'),
                                    ]),
                            ]),

                        Tabs\Tab::make('Custom Code')
                            ->icon('heroicon-o-code-bracket')
                            ->schema([
                                Section::make('Custom Styling')
                                    ->description('Add your own CSS and JavaScript to customize the appearance')
                                    ->schema([
                                        Forms\Components\Textarea::make('custom_css')
                                            ->label('Custom CSS')
                                            ->rows(10)
                                            ->helperText('Add custom CSS that will be loaded throughout the application.')
                                            ->placeholder('/* Add your custom styles here */')
                                            ->extraInputAttributes(['style' => 'font-family: monospace;']),

                                        Forms\Components\Textarea::make('custom_js')
                                            ->label('Custom JavaScript')
                                            ->rows(10)
                                            ->helperText('Add custom JavaScript that will run throughout the application.')
                                            ->placeholder('// Add your custom scripts here')
                                            ->extraInputAttributes(['style' => 'font-family: monospace;']),
                                    ]),
                            ]),

                        Tabs\Tab::make('Preview')
                            ->icon('heroicon-o-eye')
                            ->schema([
                                Section::make('Theme Preview')
                                    ->description('See how your theme will look with the current settings')
                                    ->schema([
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
