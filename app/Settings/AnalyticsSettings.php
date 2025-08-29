<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class AnalyticsSettings extends Settings
{
    public string $google_analytics_id;
    public string $google_tag_manager_id;
    public string $facebook_pixel_id;
    public bool $social_login_enabled;

    // Untuk Google Analytics configuration
    public ?string $google_analytics_property_id;
    public ?string $google_analytics_credentials_path;
    public array $google_analytics_cache_duration;
    public array $google_analytics_credentials;

    public static function group(): string
    {
        return 'analytics';
    }

    public static function defaults(): array
    {
        return [
            'google_analytics_id' => '',
            'google_tag_manager_id' => '',
            'facebook_pixel_id' => '',
            'social_login_enabled' => false,
            'google_analytics_property_id' => null,
            'google_analytics_credentials_path' => null,
            'google_analytics_cache_duration' => ['minutes' => 60],
            'google_analytics_credentials' => [],
        ];
    }
}
