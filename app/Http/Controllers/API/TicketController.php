<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\TicketResource;
use App\Models\Ticket;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() : JsonResponse
    {
        $tickets = Ticket::all();

        return response()->json([
            'tickets' => TicketResource::collection($tickets),
            'message' => 'Retrieved successfully'], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) : JsonResponse
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'user_id' => 'required|exists:users,id|max:255',
            'agent_id' => 'required|max:255|regex:/^[A-Za-z0-9_]*$/',
            'subject' => 'required|string|max:255|regex:/^[A-Za-z0-9\s\-\.,\'"]*$/',
            'message' => 'required|string|max:1000',
            'priority' => 'required|in:low,medium,high',
            'category' => 'required|string|max:50|regex:/^[A-Za-z0-9\s]*$/',
        ]);

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
    public function show(Ticket $ticket) : JsonResponse
    {
        return response()->json([
            'ticket' => new TicketResource($ticket),
            'message' => 'Retrieved successfully'], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ticket $ticket) : JsonResponse
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
    public function destroy(Ticket $ticket) : JsonResponse
    {
        $ticket->delete();

        return response()->json([
            'message' => 'Deleted successfully'], 204);
    }
}
