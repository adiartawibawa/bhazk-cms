<?php

namespace App\Filament\Clusters\Settings\Pages;

use App\Filament\Clusters\Settings;
use App\Settings\DeveloperSettings;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use Illuminate\Contracts\Support\Htmlable;

class ManageDeveloper extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static string $settings = DeveloperSettings::class;

    protected static ?string $cluster = Settings::class;

    public static function getNavigationLabel(): string
    {
        return __('resource.settings.developer.navigation.label');
    }

    public function getTitle(): string|Htmlable
    {
        return __('resource.settings.developer.title');
    }

    public static function getNavigationGroup(): string
    {
        return __('resource.settings.developer.navigation.group');
    }

    protected static ?int $navigationSort = 7;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('resource.settings.developer.sections.system.label'))
                    ->description(__('resource.settings.developer.sections.system.description'))
                    ->icon('heroicon-o-cog')
                    ->collapsible()
                    ->schema([
                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\Toggle::make('debug_mode')
                                    ->label(__('resource.settings.developer.fields.debug_mode'))
                                    ->helperText(__('resource.settings.developer.helpers.debug_mode'))
                                    ->default(config('app.debug'))
                                    ->onColor('warning')
                                    ->offColor('success')
                                    ->inline(false)
                                    ->reactive(),

                                Forms\Components\Toggle::make('maintenance_mode')
                                    ->label(__('resource.settings.developer.fields.maintenance_mode'))
                                    ->helperText(__('resource.settings.developer.helpers.maintenance_mode'))
                                    ->default(false)
                                    ->onColor('danger')
                                    ->offColor('success')
                                    ->inline(false)
                                    ->reactive(),

                                Forms\Components\Toggle::make('error_logging')
                                    ->label(__('resource.settings.developer.fields.error_logging'))
                                    ->helperText(__('resource.settings.developer.helpers.error_logging'))
                                    ->default(true)
                                    ->onColor('success')
                                    ->offColor('danger')
                                    ->inline(false),
                            ])
                            ->columns(1),
                    ]),

                Forms\Components\Section::make(__('resource.settings.developer.sections.debug_info.label'))
                    ->description(__('resource.settings.developer.sections.debug_info.description'))
                    ->icon('heroicon-o-information-circle')
                    ->collapsible()
                    ->schema([
                        Forms\Components\Placeholder::make('debug_status')
                            ->label(__('resource.settings.developer.fields.debug_status'))
                            ->content(function ($get) {
                                return $get('debug_mode') ?
                                    __('resource.settings.developer.status_messages.debug_enabled') :
                                    __('resource.settings.developer.status_messages.debug_disabled');
                            })
                            ->extraAttributes(function ($get) {
                                return [
                                    'class' => $get('debug_mode') ?
                                        'bg-yellow-100 text-yellow-800 p-4 rounded-lg border border-yellow-200' :
                                        'bg-green-100 text-green-800 p-4 rounded-lg border border-green-200'
                                ];
                            }),

                        Forms\Components\Placeholder::make('maintenance_status')
                            ->label(__('resource.settings.developer.fields.maintenance_status'))
                            ->content(function ($get) {
                                return $get('maintenance_mode') ?
                                    __('resource.settings.developer.status_messages.maintenance_enabled') :
                                    __('resource.settings.developer.status_messages.maintenance_disabled');
                            })
                            ->extraAttributes(function ($get) {
                                return [
                                    'class' => $get('maintenance_mode') ?
                                        'bg-blue-100 text-blue-800 p-4 rounded-lg border border-blue-200' :
                                        'bg-green-100 text-green-800 p-4 rounded-lg border border-green-200'
                                ];
                            }),
                    ])
                    ->visible(function ($get) {
                        return $get('debug_mode') || $get('maintenance_mode');
                    }),
            ]);
    }
}
