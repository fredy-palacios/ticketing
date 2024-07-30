<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterAgentRequest;
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
            'agent' => $agent,
            'access_token' => $accessToken,
            'message' => 'Agent created successfully'
        ]);
    }

    public function getAllAgents(): JsonResponse
    {
        $allAgents = User::getAllAgents();
        return response()->json([
            'agents' => $allAgents,
            'message' => 'Retrieved successfully'
        ]);
    }

    public function getAgentById($id): JsonResponse
    {
        $agent = User::getAgentById($id);
        return response()->json([
            'agent' => $agent,
            'message' => 'Retrieved successfully'
        ]);
    }

    public function getAllUsers(): JsonResponse
    {
        $users = User::getAllUsers();
        return response()->json([
            'users' => $users,
            'message' => 'Retrieved successfully'
        ]);
    }

    public function getUserById($id): JsonResponse
    {
        $user = User::getUserById($id);
        return response()->json([
            'user' => $user,
            'message' => 'Retrieved successfully'
        ]);
    }

    public function getAllTickets(): JsonResponse
    {
        $tickets = Ticket::all();
        return response()->json([
            'tickets' => $tickets,
            'message' => 'Retrieved successfully'
        ]);
    }

    public function getTicketById($id): JsonResponse
    {
        $ticket = Ticket::getTicketById($id);
        return response()->json([
            'ticket' => $ticket,
            'message' => 'Retrieved successfully'
        ]);
    }

    public function getAllTicketsByAgent($id): JsonResponse
    {
        $tickets = Ticket::getAllTicketsByAgent($id);
        return response()->json([
            'tickets' => $tickets,
            'message' => 'Retrieved successfully'
        ]);
    }
}
