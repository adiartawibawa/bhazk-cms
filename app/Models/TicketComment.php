<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $id
 * @property string $ticket_id
 * @property string $accountable_type
 * @property string $accountable_id
 * @property string $response
 * @property string $created_at
 * @property string $updated_at
 * @property Ticket $ticket
 */
class TicketComment extends Model
{
    use HasUuids;

    /**
     * @var array
     */
    protected $fillable = ['ticket_id', 'accountable_type', 'accountable_id', 'response', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
