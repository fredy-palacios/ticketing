<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterAgentRequest;
use App\Http\Resources\TicketResource;
use App\Http\Resources\UserResource;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    //create agent
    public function createAgent(RegisterAgentRequest $request): JsonResponse
    {
        $agent = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'agent'
        ]);

        $accessToken = $agent->createToken('authToken')->accessToken;

        return response()->json([
            'agent' => new UserResource($agent),
            'access_token' => $accessToken,
            'message' => 'Agent created successfully'
        ],201);
    }

    public function getAllAgents(): JsonResponse
    {
        $allAgents = User::getAllAgents();
        if (empty($allAgents)) {
            return response()->json(['message' => 'No agents found'],404);
        }

        return response()->json([
            'agents' => UserResource::collection($allAgents),
            'message' => 'Retrieved successfully'
        ],200);
    }

    public function getAgentById($id): JsonResponse
    {
        $agent = User::getAgentById($id);
        if (empty($agent)) {
            return response()->json(['message' => 'No agent found'],404);
        }

        return response()->json([
            'agent' => new UserResource($agent),
            'message' => 'Retrieved successfully'
        ],200);
    }

    public function getAllUsers(): JsonResponse
    {
        $users = User::getAllUsers();
        if (empty($users)) {
            return response()->json(['message' => 'No users found'],404);
        }

        return response()->json([
            'users' => UserResource::collection($users),
            'message' => 'Retrieved successfully'
        ],200);
    }

    public function getUserById($id): JsonResponse
    {
        $user = User::getUserById($id);
        if (empty($user)) {
            return response()->json(['message' => 'No user found'],404);
        }

        return response()->json([
            'user' => new UserResource($user),
            'message' => 'Retrieved successfully'
        ],200);
    }

    public function getAllTickets(): JsonResponse
    {
        $tickets = Ticket::all();
        if (empty($tickets)) {
            return response()->json(['message' => 'No tickets found'],404);
        }

        return response()->json([
            'tickets' => TicketResource::collection($tickets),
            'message' => 'Retrieved successfully'
        ],200);
    }

    public function getTicketById($id): JsonResponse
    {
        $ticket = Ticket::findById($id);

        if (!$ticket) {
            return response()->json(['message' => 'Ticket not found'],404);
        }

        return response()->json([
            'ticket' => new TicketResource($ticket),
            'message' => 'Retrieved successfully'
        ],200);
    }

    public function getAllTicketsByAgent($id): JsonResponse
    {
        $tickets = Ticket::getAllTicketsByAgent($id);
        if (empty($tickets)) {
            return response()->json(['message' => 'This agent has no tickets'],404);
        }

        return response()->json([
            'tickets' => TicketResource::collection($tickets),
            'message' => 'Retrieved successfully',
        ],200);
    }
}
