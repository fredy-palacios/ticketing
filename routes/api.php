<?php

use App\Http\Controllers\API\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserAuthController;
use App\Http\Controllers\API\TicketController;
use App\Http\Controllers\API\AgentController;
use App\Http\Controllers\API\UserController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api', 'role:user');

//Auth users
Route::post('/register',[UserAuthController::class,'register']);
Route::post('/login',[UserAuthController::class,'login']);

//User routes
Route::middleware(['auth:api', 'role:user'])->prefix('user')->group(function () {
    Route::post('/ticket/create',[TicketController::class,'store']);
    Route::get('/tickets',[UserController::class,'index']);
});

//Agent routes
Route::middleware(['auth:api', 'role:agent'])->prefix('agent')->group(function () {
    //routes to change status
    Route::post('/pending/{ticket}',[TicketController::class,'updateStatusToPending']);
    Route::post('/resolved/{ticket}',[TicketController::class,'updateStatusToResolved']);
});

//Admin routes
Route::middleware(['auth:api', 'role:admin'])->prefix('admin')->group(function () {
    //agent
    Route::post('/agent/create',[AdminController::class,'createAgent']);
    Route::get('/agents',[AdminController::class,'getAllAgents']);
    Route::get('/agent/{id}',[AdminController::class,'getAgentById']);
    //user
    Route::get('/users',[AdminController::class,'getAllUsers']);
    Route::get('/user/{id}',[AdminController::class,'getUserById']);
    //tickets
    Route::get('/tickets',[AdminController::class,'getAllTickets']);
    Route::get('/ticket/{id}',[AdminController::class,'getTicketById']);
    Route::get('/agent/{id}/tickets',[AdminController::class,'getAllTicketsByAgent']);
});

//Mixed routes Agent and User
Route::middleware(['auth:api', 'role:agent|user'])->prefix('ticket')->group(function () {
    Route::post('/close/{ticket}',[TicketController::class,'updateStatusToClosed']);
});
