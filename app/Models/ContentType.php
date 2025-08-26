<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ContentType extends Model
{
    use HasFactory, SoftDeletes, HasUuids, HasTranslations;

    /**
     * The table associated with the model.
     */
    protected $table = 'content_types';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'slug',
        'icon',
        'fields',
        'is_active',
    ];

    /**
     * The attributes that are translatable.
     */
    public $translatable = ['name', 'slug'];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'fields' => 'array',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the contents for this content type.
     */
    public function contents(): HasMany
    {
        return $this->hasMany(Content::class);
    }
}
