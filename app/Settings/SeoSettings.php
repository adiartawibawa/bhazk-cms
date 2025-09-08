<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class SeoSettings extends Settings
{
    public ?string $meta_title;
    public ?string $meta_description;
    public ?string $meta_keywords;
    public ?string $site_logo;
    public ?string $site_favicon;
    public ?string $robots_txt;

    public static function group(): string
    {
        return 'seo';
    }

    public static function defaults(): array
    {
        return [
            'meta_title' => 'My CMS - Powerful Content Management System',
            'meta_description' => 'My CMS adalah sistem manajemen konten yang powerful dan mudah digunakan',
            'meta_keywords' => 'cms, content management, website',
            'site_logo' => '/assets/images/logo.png',
            'site_favicon' => '/assets/images/favicon.ico',
            'robots_txt' => "User-agent: *\nDisallow: /admin/\nDisallow: /storage/\nSitemap: " . config('app.url') . "/sitemap.xml",
        ];
    }
}
