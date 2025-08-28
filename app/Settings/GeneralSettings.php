<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    public string $site_name;
    public string $site_url;
    public string $admin_url;
    public string $admin_email;
    public string $date_format;
    public string $time_format;
    public string $timezone;

    public static function group(): string
    {
        return 'general';
    }

    public static function defaults(): array
    {
        return [
            'site_name' => 'My CMS',
            'site_url' => config('app.url'),
            'admin_url' => config('app.url') . '/admin',
            'admin_email' => 'admin@example.com',
            'date_format' => 'd F Y',
            'time_format' => 'H:i',
            'timezone' => 'Asia/Jakarta',
        ];
    }
}
