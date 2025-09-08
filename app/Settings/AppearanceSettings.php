<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class AppearanceSettings extends Settings
{
    public ?string $active_theme;
    public ?string $primary_color;
    public ?string $secondary_color;
    public ?string $custom_css;
    public ?string $custom_js;

    public static function group(): string
    {
        return 'appearance';
    }

    public static function defaults(): array
    {
        return [
            'active_theme' => 'default',
            'primary_color'   => '#3b82f6',
            'secondary_color' => '#64748b',
            'custom_css' => '',
            'custom_js' => '',
        ];
    }
}
