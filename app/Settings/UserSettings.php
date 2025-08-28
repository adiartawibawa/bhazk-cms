<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class UserSettings extends Settings
{
    public bool $user_registration;
    public string $default_user_role;
    public bool $email_verification;

    public static function group(): string
    {
        return 'user';
    }

    public static function defaults(): array
    {
        return [
            'user_registration' => true,
            'default_user_role' => 'subscriber',
            'email_verification' => false,
        ];
    }
}
