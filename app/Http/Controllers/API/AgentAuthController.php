<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\AgentRegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Models\Agent;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class AgentAuthController extends Controller
{
    public function register(AgentRegisterRequest $request) : JsonResponse
    {
        $agent = Agent::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $accessToken = $agent->createToken('agentAuthToken')->accessToken;

        return response()->json([
            'agent' => $agent,
            'access_token' => $accessToken
        ]);
    }

    public function login (LoginRequest $request) : JsonResponse
    {
        $loginData = $request->validated(); // Get validated data
        $agent = Agent::where('email', $loginData['email'])->first();

        if (!$agent || !Hash::check($loginData['password'], $agent->password)) {
            return response()->json([
                'message' => 'Invalid credentials'],401);
        }

        $accessToken = $agent->createToken('agentAuthToken')->accessToken;

        return response()->json([
            'agent' => $agent,
            'access_token' => $accessToken
        ]);
    }
}
