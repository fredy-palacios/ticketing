<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        if (auth()->guard('api')->check()) {
            $user = auth()->user();
            $tickets = $user->tickets;

            return response()->json([
                'user' => $user,
                'tickets' => $tickets
            ]);
        }

        return response()->json([
            'message' => 'Unauthorized'
        ], 401);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }

    //close ticket
    public function closeTicket(Request $request, $ticket): JsonResponse
    {
        if (auth()->guard('api')->check()) {
            $ticket = Ticket::find($ticket);
            $ticket->status = 'closed';
            $ticket->save();
            return response()->json([
                'ticket' => $ticket,
                'message' => 'Ticket closed successfully'
            ]);
        }

        return response()->json([
            'message' => 'Unauthorized'
        ], 401);
    }
}
