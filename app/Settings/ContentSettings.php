<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class ContentSettings extends Settings
{
    public string $front_page_type;
    public ?int $front_page_id;
    public int $posts_per_page;
    public bool $comment_status;
    public bool $comment_moderation;
    public string $permalink_structure;

    public static function group(): string
    {
        return 'content';
    }

    public static function defaults(): array
    {
        return [
            'front_page_type' => 'latest_posts',
            'front_page_id' => null,
            'posts_per_page' => 10,
            'comment_status' => true,
            'comment_moderation' => true,
            'permalink_structure' => '/%postname%/',
        ];
    }
}
