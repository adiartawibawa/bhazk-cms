<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

class ContentRevision extends Model
{
    use HasFactory, HasUuids, HasTranslations;

    protected $fillable = [
        'content_id',
        'user_id',
        'title',
        'body',
        'data',
    ];

    public array $translatable = [
        'title',
        'body',
    ];

    protected function casts()
    {
        return [
            'data' => 'array',
        ];
    }

    public function content(): BelongsTo
    {
        return $this->belongsTo(Content::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
