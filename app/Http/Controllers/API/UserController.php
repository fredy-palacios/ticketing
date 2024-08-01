<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\TicketResource;
use App\Http\Resources\UserResource;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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


    }

    public function getAllTicketsByUser(): JsonResponse
    {
        $user = auth()->guard('api')->user();
        if (empty($user)) {
            return response()->json(['message' => 'No user found'], 404);
        }

        $tickets = Ticket::getAllTicketsByUser($user->id);

        return response()->json([
            'user' => new UserResource($user),
            'tickets' => TicketResource::collection($tickets),
            'message' => 'Retrieved successfully'
        ], 200);
    }
}
