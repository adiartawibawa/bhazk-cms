<?php

namespace App\Filament\Clusters\Settings\Pages;

use App\Filament\Clusters\Settings;
use App\Models\User;
use App\Settings\UserSettings;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use Illuminate\Contracts\Support\Htmlable;

class ManageUser extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string $settings = UserSettings::class;

    protected static ?string $cluster = Settings::class;

    public static function getNavigationLabel(): string
    {
        return __('resource.settings.user.navigation.label');
    }

    public function getTitle(): string | Htmlable
    {
        return __('resource.settings.user.title');
    }

    public static function getNavigationGroup(): string
    {
        return __('resource.settings.user.navigation.group');
    }

    protected static ?int $navigationSort = 5;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('resource.settings.user.sections.registration.label'))
                    ->description(__('resource.settings.user.sections.registration.description'))
                    ->icon('heroicon-o-user-plus')
                    ->collapsible()
                    ->schema([
                        Forms\Components\Toggle::make('user_registration')
                            ->label(__('resource.settings.user.fields.user_registration'))
                            ->helperText(__('resource.settings.user.helpers.user_registration'))
                            ->default(true)->onColor('success')
                            ->offColor('danger')
                            ->inline(false),

                        Forms\Components\Toggle::make('email_verification')
                            ->label(__('resource.settings.user.fields.email_verification'))
                            ->helperText(__('resource.settings.user.helpers.email_verification'))
                            ->default(true)
                            ->onColor('success')
                            ->offColor('danger')
                            ->inline(false),
                    ])
                    ->columns(1),

                Forms\Components\Section::make(__('resource.settings.user.sections.roles.label'))
                    ->description(__('resource.settings.user.sections.roles.description'))
                    ->icon('heroicon-o-shield-check')
                    ->collapsible()
                    ->schema([
                        Forms\Components\Select::make('default_user_role')
                            ->label(__('resource.settings.user.fields.default_user_role'))
                            ->options(array_intersect_key(User::defaultRoles(), array_flip([
                                User::ROLE_AUTHOR,
                                User::ROLE_EDITOR,
                                User::ROLE_USER,
                            ])))
                            ->searchable()
                            ->required()
                            ->default(User::ROLE_USER),
                    ]),
            ]);
    }
}
