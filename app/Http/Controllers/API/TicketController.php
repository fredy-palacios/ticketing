<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\TicketResource;
use App\Models\Agent;
use App\Models\Ticket;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $tickets = Ticket::all();

        return response()->json([
            'tickets' => TicketResource::collection($tickets),
            'message' => 'Retrieved successfully'], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'user_id' => 'exists:users,id|max:255',
            'subject' => 'required|string|max:255|regex:/^[A-Za-z0-9\s\-\.,\'"]*$/',
            'message' => 'required|string|max:1000',
            'priority' => 'required|in:low,medium,high',
            'category' => 'required|string|max:50|regex:/^[A-Za-z0-9\s]*$/',
        ]);

        // Assign ticket to agent_id with the least tickets
        $data['agent_id'] = $this->assign();

        if ($this->assign() === null) {
            return response()->json([
                'message' => 'No agents available'], 404);
        }

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
                'message' => 'Validation failed'], 400);
        }

        $ticket = Ticket::create($data);

        return response()->json([
            'ticket' => new TicketResource($ticket),
            'message' => 'Created successfully'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket): JsonResponse
    {
        return response()->json([
            'ticket' => new TicketResource($ticket),
            'message' => 'Retrieved successfully'], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ticket $ticket): JsonResponse
    {
        $data = $request->only('status');

        $validator = Validator::make($data, [
            'status' => 'required|in:open,pending,resolved,closed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
                'message' => 'Validation failed'], 400);
        }

        $ticket->update($data);

        return response()->json([
            'ticket' => new TicketResource($ticket),
            'message' => 'Updated successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    //Delete this method when test is done
    public function destroy(Ticket $ticket): JsonResponse
    {
        $ticket->delete();

        return response()->json([
            'message' => 'Deleted successfully'], 204);
    }

    /**
     * Assign ticket to agent with the least tickets
     */

    public function assign(): int
    {
        $agentsId = Agent::getAgentsId();// Get all agents' IDs
        $agentsWithTickets = Ticket::getAgentTicketCounts(); // Get all agents with ticket counts

        $idAgentAssigned = $this->getAgentWithLeastTickets($agentsId, $agentsWithTickets);

        if ($idAgentAssigned !== null) {
            return $idAgentAssigned;
        }

        $agentsWithZeroTickets = [];
        $agentWithLeastTickets = null;
        $minTickets = PHP_INT_MAX;

        // Find the agent with the least tickets or agents with zero tickets
        foreach ($agentsId as $agentId) {
            $ticketCount = $agentsWithTickets[$agentId]['total_tickets'] ?? 0;

            if ($ticketCount == 0) {
                $agentsWithZeroTickets[] = $agentId;
            }

            if ($ticketCount < $minTickets) {
                $minTickets = $ticketCount;
                $agentWithLeastTickets = $agentId;
            }
        }

        // If there are agents with zero tickets, select one randomly
        if (!empty($agentsWithZeroTickets)) {
            return $agentsWithZeroTickets[array_rand($agentsWithZeroTickets)];
        }

        // Otherwise, return the agent with the least tickets
        return $agentWithLeastTickets;
    }

    private function getAgentWithLeastTickets(array $idAllsAgents, array $agentsWithOpenTickets): int
    {
        $idAgents = array_column($agentsWithOpenTickets, 'agent_id');
        $idAgentsFree = array_diff($idAllsAgents, $idAgents);

        if (!empty($idAgentsFree)) {
            return array_pop($idAgentsFree);
        }

        $minTicketsElement = array_reduce($agentsWithOpenTickets, function ($carry, $item) {
            if ($carry === null || $item->open_tickets < $carry->open_tickets) {
                return $item;
            }
            return $carry;
        });

        return $minTicketsElement->agent_id;
    }
}
