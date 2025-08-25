<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ContentRevision extends Model
{
    use HasFactory, SoftDeletes, HasUuids, HasTranslations;

    /**
     * The table associated with the model.
     */
    protected $table = 'content_revisions';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'content_id',
        'author_id',
        'version',
        'title',
        'body',
        'metadata',
        'change_type',
        'change_description',
        'is_autosave',
        'diff_summary',
    ];

    /**
     * The attributes that are translatable.
     */
    public $translatable = ['title', 'body'];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'diff_summary' => 'array',
            'is_autosave' => 'boolean',
            'version' => 'integer',
        ];
    }

    /**
     * Get the content that owns the revision.
     */
    public function content(): BelongsTo
    {
        return $this->belongsTo(Content::class);
    }

    /**
     * Get the author of the revision.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
