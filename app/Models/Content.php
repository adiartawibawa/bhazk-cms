<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Content extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, HasUuids, HasTranslations;
    use InteractsWithMedia;

    /**
     * The table associated with the model.
     */
    protected $table = 'contents';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'content_type_id',
        'title',
        'slug',
        'excerpt',
        'body',
        'metadata',
        'status',
        'published_at',
        'author_id',
        'editor_id',
        'current_version',
        'comment_count',
        'featured',
        'commentable',
    ];

    /**
     * The attributes that are translatable.
     */
    public $translatable = ['title', 'slug', 'excerpt', 'body'];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'published_at' => 'datetime',
            'featured' => 'boolean',
            'commentable' => 'boolean',
            'current_version' => 'integer',
            'comment_count' => 'integer',
        ];
    }

    /**
     * The possible status values for content.
     */
    public const STATUS_DRAFT = 'draft';
    public const STATUS_PUBLISHED = 'published';
    public const STATUS_ARCHIVED = 'archived';

    /**
     * Get the content type that owns the content.
     */
    public function contentType(): BelongsTo
    {
        return $this->belongsTo(ContentType::class);
    }

    /**
     * Get the author of the content.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Get the editor of the content.
     */
    public function editor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'editor_id');
    }

    /**
     * Get the categories associated with the content.
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'content_categories')
            ->withPivot('sort_order', 'is_primary')
            ->withTimestamps();
    }

    /**
     * Get the tags associated with the content.
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'content_tags')
            ->withPivot('sort_order')
            ->withTimestamps();
    }

    /**
     * Get the revisions for the content.
     */
    public function revisions(): HasMany
    {
        return $this->hasMany(ContentRevision::class);
    }

    /**
     * Get the metrics for the content.
     */
    public function metrics(): HasMany
    {
        return $this->hasMany(ContentMetric::class);
    }

    /**
     * Get the likes for the content.
     */
    public function likes(): HasMany
    {
        return $this->hasMany(ContentLike::class);
    }

    /**
     * Get the comments for the content.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(ContentComment::class);
    }

    /**
     * Scope a query to only include published content.
     */
    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED)
            ->where('published_at', '<=', now());
    }

    /**
     * Scope a query to only include featured content.
     */
    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    /**
     * Scope a query to only include content with comments enabled.
     */
    public function scopeCommentable($query)
    {
        return $query->where('commentable', true);
    }

    /**
     * Register media collections for the content.
     */
    public function registerMediaCollections(): void
    {
        // Collection untuk gambar utama/featured image
        $this->addMediaCollection('featured_image')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'])
            ->useDisk('public');

        // Collection untuk gallery images
        $this->addMediaCollection('gallery')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'])
            ->useDisk('public');

        // Collection untuk documents (PDF, Word, etc)
        $this->addMediaCollection('documents')
            ->acceptsMimeTypes([
                'application/pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/vnd.ms-excel',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'text/plain'
            ])
            ->useDisk('public');

        // Collection untuk videos
        $this->addMediaCollection('videos')
            ->acceptsMimeTypes([
                'video/mp4',
                'video/mpeg',
                'video/quicktime',
                'video/x-msvideo',
                'video/x-ms-wmv'
            ])
            ->useDisk('public');
    }

    /**
     * Get the featured image URL.
     */
    public function getFeaturedImageUrl(string $conversion = ''): ?string
    {
        $media = $this->getFirstMedia('featured_image');
        return $media ? $media->getUrl($conversion) : null;
    }

    /**
     * Get gallery images.
     */
    public function getGalleryImages(): \Illuminate\Support\Collection
    {
        return $this->getMedia('gallery');
    }

    /**
     * Get document files.
     */
    public function getDocuments(): \Illuminate\Support\Collection
    {
        return $this->getMedia('documents');
    }

    /**
     * Scope a query to only include content with featured images.
     */
    public function scopeWithFeaturedImage($query)
    {
        return $query->whereHas('media', function ($q) {
            $q->where('collection_name', 'featured_image');
        });
    }

    /**
     * Scope a query to only include content with gallery images.
     */
    public function scopeWithGallery($query)
    {
        return $query->whereHas('media', function ($q) {
            $q->where('collection_name', 'gallery');
        });
    }
}
