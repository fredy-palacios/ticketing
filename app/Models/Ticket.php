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

    protected static function boot() : void
    {
        parent::boot();
    }


    public function user() : BelongsTo
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

    //methods to change status of ticket
    public function markAsPending(): void
    {
        $this->update([
            'status' => 'pending',
            'pending_at' => now(),
        ]);
    }

    public function markAsResolved(): void
    {
        $this->update([
            'status' => 'resolved',
            'resolved_at' => now(),
        ]);
    }

    public function markAsClosed(): void
    {
        $this->update([
            'status' => 'closed',
            'closed_at' => now(),
        ]);
    }

    public static function findById(int $id): ?Ticket
    {
        return Ticket::where('id', $id)->first();
    }

    public static function getAllTicketsByAgent(int $agentId): ?array
    {
        return Ticket::where('agent_id', $agentId)->get()->all();
    }
}
