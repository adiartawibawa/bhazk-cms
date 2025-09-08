<?php

if (! function_exists('settings')) {
    function settings(string $key, $default = null)
    {
        [$group, $field] = explode('.', $key);

        $map = [
            'general'    => \App\Settings\GeneralSettings::class,
            'appearance' => \App\Settings\AppearanceSettings::class,
            'analytics'  => \App\Settings\AnalyticsSettings::class,
            'content'    => \App\Settings\ContentSettings::class,
            'developer'  => \App\Settings\DeveloperSettings::class,
            'language'   => \App\Settings\LanguageSettings::class,
            'media'      => \App\Settings\MediaSettings::class,
            'seo'        => \App\Settings\SeoSettings::class,
            'user'       => \App\Settings\UserSettings::class,
        ];

        if (! isset($map[$group])) {
            return $default;
        }

        return app($map[$group])->{$field} ?? $default;
    }
}
