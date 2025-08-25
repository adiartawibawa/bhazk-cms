<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CommentLike extends Model
{
    use HasFactory, HasUuids;

    /**
     * The table associated with the model.
     */
    protected $table = 'comment_likes';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'comment_id',
        'user_id',
    ];

    /**
     * Get the comment that was liked.
     */
    public function comment(): BelongsTo
    {
        return $this->belongsTo(ContentComment::class);
    }

    /**
     * Get the user who liked the comment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
