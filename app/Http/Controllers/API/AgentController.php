<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Ticket;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        if (auth()->guard('agent')->check()) {
            $agent = auth()->guard('agent')->user();
            $tickets = $agent->tickets;

            return response()->json([
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

    }

    /**
     * Display the specified resource.
     */
    public function get(Request $request)
    {
        $id_agent = $request->input('id_agent');
        $agent = (new Agent)->find($id_agent);

        return response()->json([
            'message' => 'Unauthorized'
        ], 401);
    }

    public function getAll(Request $request)
    {
        $agents = Agent::all();

        return response()->json([
            'agents' => $agents
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Agent $agent)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Agent $agent)
    {
        //
    }

    //Change status of ticket
    public function changeStatus(Request $request, Agent $agent, Ticket $ticket): JsonResponse
    {
        if ($agent->id !== $ticket->agent_id) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        $changeStatus = $request->validate([
            'status' => 'required|in:pending,resolved,closed'
        ]);

        $ticket->update($changeStatus);

        return response()->json([
            'ticket' => $ticket,
            'message' => 'Status updated successfully'
        ]);
    }
}
