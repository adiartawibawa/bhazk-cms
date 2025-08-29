<?php

namespace App\Filament\Clusters\Settings\Pages;

use App\Filament\Clusters\Settings;
use App\Settings\LanguageSettings;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageLanguage extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-language';

    protected static string $settings = LanguageSettings::class;

    protected static ?string $cluster = Settings::class;

    protected static ?string $navigationLabel = 'Language Settings';

    protected static ?string $title = 'Manage Language Settings';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Language Settings')
                    ->description('Configure the language preferences for your application')
                    ->icon('heroicon-o-language')
                    ->schema([
                        Forms\Components\Select::make('default_language')
                            ->label('Default Language')
                            ->options([
                                'id' => 'Indonesian',
                                'en' => 'English',
                            ])
                            ->required()
                            ->default('id')
                            ->searchable(),

                        Forms\Components\CheckboxList::make('supported_languages')
                            ->label('Supported Languages')
                            ->options([
                                'id' => 'Indonesian',
                                'en' => 'English',
                            ])
                            ->columns(2)
                            ->default(['id', 'en'])
                            ->required(),

                        Forms\Components\Toggle::make('auto_detect_language')
                            ->label('Auto Detect Language')
                            ->helperText('Jika aktif, bahasa user akan dipilih berdasarkan browser/locale mereka.')
                            ->default(true),
                    ]),
            ]);
    }
}
