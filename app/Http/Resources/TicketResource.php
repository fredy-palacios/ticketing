<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property int $id
 * @property int $agent_id
 * @property int $user_id
 * @property string $subject
 * @property string $message
 * @property string $status
 * @property string $priority
 * @property string $category
 * @property string $pending_at
 * @property string $resolved_at
 * @property string $closed_at
 */

class TicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'agent_id' => $this->agent_id,
            'user_id' => $this->user_id,
            'subject' => $this->subject,
            'message' => $this->message,
            'status' => $this->status,
            'priority' => $this->priority,
            'category' => $this->category,
            'pending_at' => $this->pending_at,
            'resolved_at' => $this->resolved_at,
            'closed_at' => $this->closed_at,
        ];
    }
}
