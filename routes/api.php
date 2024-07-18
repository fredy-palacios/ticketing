<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\TicketController;
use App\Http\Controllers\API\AgentAuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::get('/agent', function (Request $request) {
    return $request->user();
})->middleware('auth:agent');

Route::post('/agent/register',[AgentAuthController::class,'register']);
Route::post('/agent/login',[AgentAuthController::class,'login']);

Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);

Route::apiResource('/ticket', TicketController::class)->middleware('auth:api');
