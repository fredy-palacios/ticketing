<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserAuthController;
use App\Http\Controllers\API\TicketController;
use App\Http\Controllers\API\AgentAuthController;
use App\Http\Controllers\API\AgentController;
use App\Http\Controllers\API\UserController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::get('/agent', function (Request $request) {
    return $request->user();
})->middleware('auth:agent');

//Auth agents
Route::post('/agent/register',[AgentAuthController::class,'register']);
Route::post('/agent/login',[AgentAuthController::class,'login']);

//Auth users
Route::post('/register',[UserAuthController::class,'register']);
Route::post('/login',[UserAuthController::class,'login']);

//Agent routes
Route::get('/agent/tickets',[AgentController::class,'index'])->middleware('auth:agent');
Route::post('/agent/{agent}/ticket/{ticket}/status',[AgentController::class,'changeStatus'])->middleware('auth:agent');

//User routes
Route::get('/user/tickets',[UserController::class,'index'])->middleware('auth:api');
Route::post('/user/ticket/create',[TicketController::class,'store'])->middleware('auth:api');
Route::post('/user/close/{ticket}',[UserController::class,'closeTicket'])->middleware('auth:api');

Route::middleware(['auth:api'])->group(function () {
    //routes to change status
    Route::post('/user/pending/{ticket}',[TicketController::class,'updateStatusToPending']);
    Route::post('/user/resolved/{ticket}',[TicketController::class,'updateStatusToResolved']);
    Route::post('/user/close/{ticket}',[TicketController::class,'updateStatusToClosed']);
});
