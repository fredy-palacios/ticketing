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
    private string $status;
    private \Illuminate\Support\Carbon $closed_at;
    private \Illuminate\Support\Carbon $resolved_at;
    private \Illuminate\Support\Carbon $pending_at;

    protected static function boot() : void
    {
        parent::boot();
    }

    public function close() : void
    {
        $this->status = 'closed';
        $this->closed_at = now();
        $this->save();
    }

    public function resolve() : void
    {
        $this->status = 'resolved';
        $this->resolved_at = now();
        $this->save();
    }

    public function pending() : void
    {
        $this->status = 'pending';
        $this->pending_at = now();
        $this->save();
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
