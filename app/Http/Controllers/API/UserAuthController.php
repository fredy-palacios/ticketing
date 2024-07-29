<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserAuthController extends Controller
{
    public function register(RegisterUserRequest $request) : JsonResponse
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user' //Default role
        ]);

        $accessToken = $user->createToken('authToken')->accessToken;

        return response()->json([
            'user' => $user,
            'access_token' => $accessToken
        ]);
    }

    public function login (LoginRequest $request) : JsonResponse
    {
        $loginData = $request->validated(); // Get validated data
        $user = User::where('email', $loginData['email'])->first();

        if (!$user || !Hash::check($loginData['password'], $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials'],401);
        }

        $accessToken = $user->createToken('authToken')->accessToken;

        return response()->json([
            'user' => $user,
            'access_token' => $accessToken
        ]);
    }
}
