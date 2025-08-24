<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class ContentType extends Model
{
    use HasUuids, HasTranslations;

    protected $fillable = [
        'name',
        'slug',
        'fileds',
    ];

    protected function casts(): array
    {
        return [
            'fields' => 'array',
        ];
    }

    public array $translatable = [
        'name',
        'slug'
    ];

    public function contents(): HasMany
    {
        return $this->hasMany(Content::class, 'content_type_id');
    }
}
