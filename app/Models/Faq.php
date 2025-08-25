<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Faq extends Model
{
    use HasFactory, SoftDeletes, HasUuids, HasTranslations;

    /**
     * The table associated with the model.
     */
    protected $table = 'faqs';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'question',
        'answer',
        'sort_order',
        'faq_category_id',
        'is_active',
        'views_count',
        'helpful_count',
        'not_helpful_count',
        'helpfulness_ratio',
    ];

    /**
     * The attributes that are translatable.
     */
    public $translatable = ['question', 'answer'];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'is_active' => 'boolean',
            'views_count' => 'integer',
            'helpful_count' => 'integer',
            'not_helpful_count' => 'integer',
            'helpfulness_ratio' => 'float',
        ];
    }

    /**
     * Get the category that owns the FAQ.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(FaqCategory::class);
    }

    /**
     * Scope a query to only include active FAQs.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to order FAQs by sort order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc');
    }
}
