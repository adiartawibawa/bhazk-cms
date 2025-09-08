<?php

namespace App\Filament\Clusters\Settings\Pages;

use App\Filament\Clusters\Settings;
use App\Settings\GeneralSettings;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use Illuminate\Support\Facades\Date;
use DateTimeZone;
use Illuminate\Contracts\Support\Htmlable;

class ManageGeneral extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static string $settings = GeneralSettings::class;

    protected static ?string $cluster = Settings::class;

    public static function getNavigationLabel(): string
    {
        return __('resource.settings.general.navigation.label');
    }

    public function getTitle(): string|Htmlable
    {
        return __('resource.settings.general.title');
    }

    public static function getNavigationGroup(): string
    {
        return __('resource.settings.general.navigation.group');
    }

    protected static ?int $navigationSort = 1;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('resource.settings.general.sections.general.label'))
                    ->description(__('resource.settings.general.sections.general.description'))
                    ->icon('heroicon-o-globe-alt')
                    ->collapsible()
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('site_name')
                                    ->label(__('resource.settings.general.fields.site_name'))
                                    ->required()
                                    ->maxLength(255)
                                    ->columnSpan(1),

                                Forms\Components\TextInput::make('admin_email')
                                    ->label(__('resource.settings.general.fields.admin_email'))
                                    ->email()
                                    ->required()
                                    ->maxLength(255)
                                    ->columnSpan(1),
                            ]),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('site_url')
                                    ->label(__('resource.settings.general.fields.site_url'))
                                    ->url()
                                    ->required()
                                    ->maxLength(255)
                                    ->prefixIcon('heroicon-o-link')
                                    ->columnSpan(1),

                                Forms\Components\TextInput::make('admin_url')
                                    ->label(__('resource.settings.general.fields.admin_url'))
                                    ->url()
                                    ->required()
                                    ->maxLength(255)
                                    ->prefixIcon('heroicon-o-lock-closed')
                                    ->helperText(__('resource.settings.general.placeholders.admin_url'))
                                    ->columnSpan(1),
                            ]),
                    ]),

                Forms\Components\Section::make(__('resource.settings.general.sections.datetime.label'))
                    ->description(__('resource.settings.general.sections.datetime.description'))
                    ->icon('heroicon-o-clock')
                    ->collapsible()
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\Select::make('date_format')
                                    ->label(__('resource.settings.general.fields.date_format'))
                                    ->options([
                                        'd F Y'   => now()->format('d F Y') . ' (d F Y)',
                                        'd/m/Y'   => now()->format('d/m/Y') . ' (d/m/Y)',
                                        'm-d-Y'   => now()->format('m-d-Y') . ' (m-d-Y)',
                                        'Y-m-d'   => now()->format('Y-m-d') . ' (Y-m-d)',
                                    ])
                                    ->searchable()
                                    ->columnSpan(1),

                                Forms\Components\Select::make('time_format')
                                    ->label(__('resource.settings.general.fields.time_format'))
                                    ->options([
                                        'H:i'    => now()->format('H:i') . ' (24h)',
                                        'h:i A'  => now()->format('h:i A') . ' (12h AM/PM)',
                                    ])
                                    ->searchable()
                                    ->columnSpan(1),

                                Forms\Components\Select::make('timezone')
                                    ->label(__('resource.settings.general.fields.timezone'))
                                    ->options(collect(DateTimeZone::listIdentifiers())
                                        ->mapWithKeys(fn($tz) => [$tz => $tz]))
                                    ->searchable()
                                    ->default('Asia/Jakarta')
                                    ->columnSpan(1),
                            ]),

                        Forms\Components\Placeholder::make('datetime_preview')
                            ->label(__('resource.settings.general.fields.datetime_preview'))
                            ->content(function ($get) {
                                $dateFormat = $get('date_format') ?? 'd F Y';
                                $timeFormat = $get('time_format') ?? 'H:i';
                                $timezone = $get('timezone') ?? 'Asia/Jakarta';

                                $now = now()->setTimezone($timezone);
                                return $now->format($dateFormat) . ' ' . $now->format($timeFormat);
                            })
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
