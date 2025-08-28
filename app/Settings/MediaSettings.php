<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;
use Spatie\LaravelSettings\SettingsCasts\ArraySettingsCast;

class MediaSettings extends Settings
{
    public string $upload_organization;
    public array $thumbnail_size;
    public array $medium_size;
    public array $large_size;
    public int $max_upload_size;

    public static function group(): string
    {
        return 'media';
    }

    public static function casts(): array
    {
        return [
            'thumbnail_size' => ArraySettingsCast::class,
            'medium_size' => ArraySettingsCast::class,
            'large_size' => ArraySettingsCast::class,
        ];
    }

    public static function defaults(): array
    {
        return [
            'upload_organization' => 'year_month',
            'thumbnail_size' => ['width' => 150, 'height' => 150],
            'medium_size' => ['width' => 300, 'height' => 300],
            'large_size' => ['width' => 1024, 'height' => 1024],
            'max_upload_size' => 2048,
        ];
    }
}
