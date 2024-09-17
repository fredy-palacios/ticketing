<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Ticket;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function authorizeMessageAccess(Ticket $ticket): void
    {
        if ($ticket->user_id !== Auth::id() && $ticket->agent_id !== Auth::id()) {
            abort(401, 'Unauthorized');
        }
    }
    public function store(Request $request, $ticketId): JsonResponse
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $ticket = Ticket::findById($ticketId);

        $this->authorizeMessageAccess($ticket);

        $ticket->messages()->create([
            'sender_id' => Auth::id(),
            'message' => $request->message,
            'ticket_id' => $ticketId
        ]);

        return response()->json([
            'message' => 'Message sent',
            'ticket' => $ticket
        ], 201);
    }

    public function getMessages($ticketId): JsonResponse
    {
        $ticket = Ticket::findById($ticketId);

        $this->authorizeMessageAccess($ticket);

        return response()->json([
            'messages' => Message::getAllMessages($ticketId),
            'ticket' => $ticket
        ], 200);
    }
}
