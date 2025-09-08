<?php

namespace App\Filament\Clusters\Settings\Pages;

use App\Filament\Clusters\Settings;
use App\Settings\LanguageSettings;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use Illuminate\Contracts\Support\Htmlable;

class ManageLanguage extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-language';

    protected static string $settings = LanguageSettings::class;

    protected static ?string $cluster = Settings::class;

    public static function getNavigationLabel(): string
    {
        return __('resource.settings.language.navigation.label');
    }

    public function getTitle(): string|Htmlable
    {
        return __('resource.settings.language.title');
    }

    public static function getNavigationGroup(): string
    {
        return __('resource.settings.language.navigation.group');
    }

    protected static ?int $navigationSort = 4;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('resource.settings.language.sections.language.label'))
                    ->description(__('resource.settings.language.sections.language.description'))
                    ->icon('heroicon-o-language')
                    ->collapsible()
                    ->schema([
                        Forms\Components\Select::make('default_language')
                            ->label(__('resource.settings.language.fields.default_language'))
                            ->options([
                                'id' => __('resource.settings.language.options.languages.id'),
                                'en' => __('resource.settings.language.options.languages.en'),
                            ])
                            ->required()
                            ->default('id')
                            ->searchable(),

                        Forms\Components\CheckboxList::make('supported_languages')
                            ->label(__('resource.settings.language.fields.supported_languages'))
                            ->options([
                                'id' => __('resource.settings.language.options.languages.id'),
                                'en' => __('resource.settings.language.options.languages.en'),
                            ])
                            ->columns(2)
                            ->default(['id', 'en'])
                            ->required(),

                        Forms\Components\Toggle::make('auto_detect_language')
                            ->label(__('resource.settings.language.fields.auto_detect_language'))
                            ->helperText(__('resource.settings.language.helpers.auto_detect_language'))
                            ->default(true),
                    ]),
            ]);
    }
}
