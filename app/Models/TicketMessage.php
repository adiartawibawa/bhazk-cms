<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TicketMessage extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    /**
     * The table associated with the model.
     */
    protected $table = 'ticket_messages';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'ticket_id',
        'user_id',
        'message',
        'message_type',
        'is_internal',
        'is_first_response',
        'attachments',
        'ip_address',
        'user_agent',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'attachments' => 'array',
            'is_internal' => 'boolean',
            'is_first_response' => 'boolean',
        ];
    }

    /**
     * The possible message types.
     */
    public const MESSAGE_TYPE_USER = 'user';
    public const MESSAGE_TYPE_ADMIN = 'admin';
    public const MESSAGE_TYPE_SYSTEM = 'system';

    /**
     * Get the ticket that owns the message.
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    /**
     * Get the user who sent the message.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(TicketAttachment::class, 'message_id');
    }

    /**
     * Check if the message is from a user.
     */
    public function isFromUser(): bool
    {
        return $this->message_type === self::MESSAGE_TYPE_USER;
    }

    /**
     * Check if the message is from an admin.
     */
    public function isFromAdmin(): bool
    {
        return $this->message_type === self::MESSAGE_TYPE_ADMIN;
    }

    /**
     * Check if the message is a system message.
     */
    public function isSystemMessage(): bool
    {
        return $this->message_type === self::MESSAGE_TYPE_SYSTEM;
    }
}
