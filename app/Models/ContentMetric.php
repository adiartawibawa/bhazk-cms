<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ContentMetric extends Model
{
    use HasFactory, HasUuids;

    /**
     * The table associated with the model.
     */
    protected $table = 'content_metrics';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'content_id',
        'views_count',
        'unique_views_count',
        'likes_count',
        'shares_count',
        'comments_count',
        'downloads_count',
        'reading_time_seconds',
        'engagement_rate',
        'metric_date',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'views_count' => 'integer',
            'unique_views_count' => 'integer',
            'likes_count' => 'integer',
            'shares_count' => 'integer',
            'comments_count' => 'integer',
            'downloads_count' => 'integer',
            'reading_time_seconds' => 'integer',
            'engagement_rate' => 'float',
            'metric_date' => 'date',
        ];
    }

    /**
     * Get the content that owns the metrics.
     */
    public function content(): BelongsTo
    {
        return $this->belongsTo(Content::class);
    }
}
