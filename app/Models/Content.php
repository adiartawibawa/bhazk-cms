<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Content extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'type',
        'author_id',
        'slug',
        'slug_index',
        'latest_revision_id',
        'published_revision_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'slug' => 'array',
        ];
    }

    /**
     * Get the author of this content.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Get all revisions for this content.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function revisions(): HasMany
    {
        return $this->hasMany(ContentRevision::class);
    }

    /**
     * Get the latest revision for this content.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function latestRevision(): HasOne
    {
        return $this->hasOne(ContentRevision::class, 'id', 'latest_revision_id');
    }

    /**
     * Get the published revision for this content.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function publishedRevision(): HasOne
    {
        return $this->hasOne(ContentRevision::class, 'id', 'published_revision_id');
    }

    /**
     * Get the custom field values for this content.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function customFieldValues(): HasMany
    {
        return $this->hasMany(ContentCustomFieldValue::class);
    }

    /**
     * Get the terms associated with this content.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function terms(): BelongsToMany
    {
        return $this->belongsToMany(Term::class, 'content_terms');
    }
}
