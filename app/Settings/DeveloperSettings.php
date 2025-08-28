<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class DeveloperSettings extends Settings
{
    public bool $debug_mode;
    public bool $maintenance_mode;
    public bool $error_logging;

    public static function group(): string
    {
        return 'developer';
    }

    public static function defaults(): array
    {
        return [
            'debug_mode' => config('app.debug'),
            'maintenance_mode' => false,
            'error_logging' => true,
        ];
    }
}
