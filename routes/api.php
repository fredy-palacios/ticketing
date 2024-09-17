<?php

use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\MessageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserAuthController;
use App\Http\Controllers\API\TicketController;
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
    //Close ticket
    Route::post('/ticket/{ticket}/close',[TicketController::class,'updateStatusToClosed']);
    //Get all tickets by user
    Route::get('/tickets',[UserController::class,'getAllTicketsByUser']);
    //Create message
    Route::post('/ticket/{ticket}/message',[MessageController::class,'store']);
    //Get all messages
    Route::get('/ticket/{ticket}/messages',[MessageController::class,'getMessages']);

});

//Agent routes
Route::middleware(['auth:api', 'role:agent'])->prefix('agent')->group(function () {
    //routes to change status
    Route::post('/ticket/pending/{ticket}',[TicketController::class,'updateStatusToPending']);
    Route::post('/ticket/resolved/{ticket}',[TicketController::class,'updateStatusToResolved']);
    Route::post('/ticket/closed/{ticket}',[TicketController::class,'updateStatusToClosed']);
    //create message
    Route::post('/ticket/{ticket}/message',[MessageController::class,'store']);
    //get all messages
    Route::get('/ticket/{ticket}/messages',[MessageController::class,'getMessages']);
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
    Route::get('/user/{id}/tickets',[AdminController::class,'getAllTicketsByUser']);
    //tickets
    Route::get('/tickets',[AdminController::class,'getAllTickets']);
    Route::get('/ticket/{id}',[AdminController::class,'getTicketById']);
    Route::get('/agent/{id}/tickets',[AdminController::class,'getAllTicketsByAgent']);
    Route::get('tickets/pending',[AdminController::class,'getAllPendingTickets']);
    Route::get('tickets/resolved',[AdminController::class,'getAllResolvedTickets']);
    Route::get('tickets/closed',[AdminController::class,'getAllClosedTickets']);
});
