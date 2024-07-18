<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AgentAuthController extends Controller
{
    public function register(Request $request) : JsonResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:agents',
            'password' => 'required|confirmed'
        ]);

        $validatedData['password'] = Hash::make($request->password);

        $agent = Agent::create($validatedData);

        $accessToken = $agent->createToken('agentAuthToken')->accessToken;

        return response()->json([
            'agent' => $agent,
            'access_token' => $accessToken
        ]);

    }

    public function login (Request $request) : JsonResponse
    {
        $loginData = $request->validate([
            'email'=> 'email|required',
            'password' => 'required'
        ]);

        if (!Auth::guard('agent')->attempt($loginData)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $agent = Auth::guard('agent')->user();
        $accessToken = $agent->createToken('agentAuthToken')->accessToken;

        return response()->json([
            'agent' => $agent,
            'access_token' => $accessToken,
        ]);
    }
}