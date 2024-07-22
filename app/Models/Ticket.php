<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'agent_id',
        'subject',
        'message',
        'status',
        'priority',
        'category',
        'pending_at',
        'resolved_at',
        'closed_at',
    ];

    // relationships


    protected static function boot() : void
    {
        parent::boot();

        static::updating(function ($ticket) {
            if ($ticket->isDirty('status') && $ticket->status === 'pending') {
                $ticket->pending_at = now();
            }
            if ($ticket->isDirty('status') && $ticket->status === 'resolved') {
                $ticket->resolved_at = now();
            }
            if ($ticket->isDirty('status') && $ticket->status === 'closed') {
                $ticket->closed_at = now();
            }
        });
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function agent() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function getAgentTicketCounts(): array
    {
        return DB::table('tickets')->select('agent_id', DB::raw('count(agent_id) as open_tickets'))
            ->groupBy('agent_id')
            ->get()
            ->all();
    }
}
