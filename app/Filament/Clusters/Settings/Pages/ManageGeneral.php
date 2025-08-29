<?php

namespace App\Filament\Clusters\Settings\Pages;

use App\Filament\Clusters\Settings;
use App\Settings\GeneralSettings;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use Illuminate\Support\Facades\Date;
use DateTimeZone;

class ManageGeneral extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static string $settings = GeneralSettings::class;

    protected static ?string $cluster = Settings::class;

    protected static ?string $navigationLabel = 'General Settings';

    protected static ?string $title = 'Manage General Settings';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('General Settings')
                    ->description('Configure basic site information and global settings')
                    ->icon('heroicon-o-globe-alt')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('site_name')
                                    ->label('Site Name')
                                    ->required()
                                    ->maxLength(255)
                                    ->columnSpan(1),

                                Forms\Components\TextInput::make('admin_email')
                                    ->label('Admin Email')
                                    ->email()
                                    ->required()
                                    ->maxLength(255)
                                    ->columnSpan(1),
                            ]),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('site_url')
                                    ->label('Site URL')
                                    ->url()
                                    ->required()
                                    ->maxLength(255)
                                    ->prefixIcon('heroicon-o-link')
                                    ->columnSpan(1),

                                Forms\Components\TextInput::make('admin_url')
                                    ->label('Admin URL')
                                    ->url()
                                    ->required()
                                    ->maxLength(255)
                                    ->prefixIcon('heroicon-o-lock-closed')
                                    ->helperText('URL untuk akses dashboard admin.')
                                    ->columnSpan(1),
                            ]),
                    ]),

                Forms\Components\Section::make('Date & Time Settings')
                    ->description('Configure how dates and times are displayed throughout the site')
                    ->icon('heroicon-o-clock')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\Select::make('date_format')
                                    ->label('Date Format')
                                    ->options([
                                        'd F Y'   => now()->format('d F Y') . ' (d F Y)',
                                        'd/m/Y'   => now()->format('d/m/Y') . ' (d/m/Y)',
                                        'm-d-Y'   => now()->format('m-d-Y') . ' (m-d-Y)',
                                        'Y-m-d'   => now()->format('Y-m-d') . ' (Y-m-d)',
                                    ])
                                    ->searchable()
                                    ->columnSpan(1),

                                Forms\Components\Select::make('time_format')
                                    ->label('Time Format')
                                    ->options([
                                        'H:i'    => now()->format('H:i') . ' (24h)',
                                        'h:i A'  => now()->format('h:i A') . ' (12h AM/PM)',
                                    ])
                                    ->searchable()
                                    ->columnSpan(1),

                                Forms\Components\Select::make('timezone')
                                    ->label('Timezone')
                                    ->options(collect(DateTimeZone::listIdentifiers())
                                        ->mapWithKeys(fn($tz) => [$tz => $tz]))
                                    ->searchable()
                                    ->default('Asia/Jakarta')
                                    ->columnSpan(1),
                            ]),

                        Forms\Components\Placeholder::make('datetime_preview')
                            ->label('Current Preview')
                            ->content(function ($get) {
                                $dateFormat = $get('date_format') ?? 'd F Y';
                                $timeFormat = $get('time_format') ?? 'H:i';
                                $timezone = $get('timezone') ?? 'Asia/Jakarta';

                                $now = now()->setTimezone($timezone);
                                return $now->format($dateFormat) . ' ' . $now->format($timeFormat);
                            })
                            ->extraAttributes(['class' => 'bg-gray-100 p-4 rounded-lg font-mono text-center text-lg'])
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
