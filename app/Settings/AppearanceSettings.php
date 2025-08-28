<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class AppearanceSettings extends Settings
{
    public string $active_theme;
    public string $custom_css;
    public string $custom_js;

    public static function group(): string
    {
        return 'appearance';
    }

    public static function defaults(): array
    {
        return [
            'active_theme' => 'default',
            'custom_css' => '',
            'custom_js' => '',
        ];
    }
}
