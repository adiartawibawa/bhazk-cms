<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContentMetric extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'content_id',
        'views_count',
        'likes_count',
        'shares_count'
    ];

    public function content(): BelongsTo
    {
        return $this->belongsTo(Content::class);
    }
}
