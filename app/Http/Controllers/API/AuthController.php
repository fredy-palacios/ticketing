<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\JsonResponse;

class AuthController extends Controller
{
    public function register(Request $request) : JsonResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed'
        ]);

        $validatedData['password'] = Hash::make($request->password);

        $user = User::create($validatedData);

        $accessToken = $user->createToken('authToken')->accessToken;

        return response()->json([
            'user' => $user,
            'access_token' => $accessToken
        ]);

    }

    public function login (Request $request) : JsonResponse
    {
        $loginData = $request->validate([
            'email'=> 'email|required',
            'password' => 'required'
        ]);

        if (!auth()->attempt($loginData)) {
            return response()->json([
                'message' => 'Invalid credentials'],401);
        }

        $accessToken = auth()->user()->createToken('authToken')->accessToken;

        return response()->json([
            'user' => auth()->user(),
            'access_token' => $accessToken
        ]);
    }


}
