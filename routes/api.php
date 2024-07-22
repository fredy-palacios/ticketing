<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\TicketController;
use App\Http\Controllers\API\AgentAuthController;
use App\Http\Controllers\API\AgentController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::get('/agent', function (Request $request) {
    return $request->user();
})->middleware('auth:agent');

//Auth routes
Route::post('/agent/register',[AgentAuthController::class,'register']);
Route::post('/agent/login',[AgentAuthController::class,'login']);

Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);

//Ticket routes
Route::apiResource('/ticket', TicketController::class)->middleware('auth:api');

//Agent routes
Route::post('/agent/{agent}/ticket/{ticket}/status',[AgentController::class,'changeStatus'])->middleware('auth:agent');
Route::get('/agent/tickets',[AgentController::class,'index'])->middleware('auth:agent');
