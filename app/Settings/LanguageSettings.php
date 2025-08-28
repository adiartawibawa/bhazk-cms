<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;
use Spatie\LaravelSettings\SettingsCasts\ArraySettingsCast;

class LanguageSettings extends Settings
{
    public string $default_language;
    public array $supported_languages;
    public bool $auto_detect_language;

    public static function group(): string
    {
        return 'language';
    }

    public static function casts(): array
    {
        return [
            'supported_languages' => ArraySettingsCast::class,
        ];
    }

    public static function defaults(): array
    {
        return [
            'default_language' => 'id',
            'supported_languages' => ['id', 'en'],
            'auto_detect_language' => true,
        ];
    }
}
