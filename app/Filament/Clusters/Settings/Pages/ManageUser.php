<?php

namespace App\Filament\Clusters\Settings\Pages;

use App\Filament\Clusters\Settings;
use App\Models\User;
use App\Settings\UserSettings;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageUser extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string $settings = UserSettings::class;

    protected static ?string $cluster = Settings::class;

    protected static ?string $navigationLabel = 'User Settings';

    protected static ?string $title = 'Manage User Settings';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Users Registration')
                    ->description('Set how new users can register to the system')
                    ->icon('heroicon-o-user-plus')
                    ->collapsible()
                    ->schema([
                        Forms\Components\Toggle::make('user_registration')
                            ->label('Allow User Registration')
                            ->helperText('If enabled, new users can register themselves.')
                            ->default(true)->onColor('success')
                            ->offColor('danger')
                            ->inline(false),

                        Forms\Components\Toggle::make('email_verification')
                            ->label('Require Email Verification')
                            ->helperText('If enabled, users must verify their email after registering.')
                            ->default(true)->default(true)
                            ->onColor('success')
                            ->offColor('danger')
                            ->inline(false),
                    ])
                    ->columns(1),
                Forms\Components\Section::make('Default Roles')
                    ->description('Set default roles for new users')
                    ->icon('heroicon-o-shield-check')
                    ->collapsible()
                    ->schema([
                        Forms\Components\Select::make('default_role')
                            ->label('Default Role for New Users')
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
