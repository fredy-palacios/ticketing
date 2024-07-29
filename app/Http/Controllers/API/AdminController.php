<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\AgentRegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    //create agent
    public function createAgent(AgentRegisterRequest $request): JsonResponse
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
}
