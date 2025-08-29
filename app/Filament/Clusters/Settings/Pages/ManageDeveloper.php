<?php

namespace App\Filament\Clusters\Settings\Pages;

use App\Filament\Clusters\Settings;
use App\Settings\DeveloperSettings;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageDeveloper extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static string $settings = DeveloperSettings::class;

    protected static ?string $cluster = Settings::class;

    protected static ?string $navigationLabel = 'Developer Settings';

    protected static ?string $title = 'Manage Developer Settings';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('System Settings')
                    ->description('Configure system behavior and error handling')
                    ->icon('heroicon-o-cog')
                    ->schema([
                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\Toggle::make('debug_mode')
                                    ->label('Enable Debug Mode')
                                    ->helperText('When enabled, the application will display detailed error messages (recommended for development only).')
                                    ->default(config('app.debug'))
                                    ->onColor('warning')
                                    ->offColor('success')
                                    ->inline(false)
                                    ->reactive(), // Tambahkan reactive() agar perubahan langsung terlihat

                                Forms\Components\Toggle::make('maintenance_mode')
                                    ->label('Maintenance Mode')
                                    ->helperText('When enabled, all users will be redirected to the maintenance page.')
                                    ->default(false)
                                    ->onColor('danger')
                                    ->offColor('success')
                                    ->inline(false)
                                    ->reactive(), // Tambahkan reactive() agar perubahan langsung terlihat

                                Forms\Components\Toggle::make('error_logging')
                                    ->label('Enable Error Logging')
                                    ->helperText('When enabled, all errors will be logged to the Laravel log files.')
                                    ->default(true)
                                    ->onColor('success')
                                    ->offColor('danger')
                                    ->inline(false),
                            ])
                            ->columns(1),
                    ]),

                Forms\Components\Section::make('Debug Information')
                    ->description('Current system status and recommendations')
                    ->icon('heroicon-o-information-circle')
                    ->schema([
                        Forms\Components\Placeholder::make('debug_status')
                            ->label('Debug Mode Status')
                            ->content(function ($get) {
                                return $get('debug_mode') ?
                                    '⚠️ Debug mode is ENABLED - Not recommended for production' :
                                    '✅ Debug mode is disabled - Recommended for production';
                            })
                            ->extraAttributes(function ($get) {
                                return [
                                    'class' => $get('debug_mode') ?
                                        'bg-yellow-100 text-yellow-800 p-4 rounded-lg border border-yellow-200' :
                                        'bg-green-100 text-green-800 p-4 rounded-lg border border-green-200'
                                ];
                            }),

                        Forms\Components\Placeholder::make('maintenance_status')
                            ->label('Maintenance Mode Status')
                            ->content(function ($get) {
                                return $get('maintenance_mode') ?
                                    '🔧 Maintenance mode is ENABLED - Users will see maintenance page' :
                                    '✅ Maintenance mode is disabled - Site is accessible to all users';
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
