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
    Route::post('/agent/create',[AdminController::class,'createAgent']);
    //Route::get('/agents',[AgentController::class,'getAll']);
    //Route::get('/agent/{agent}',[AgentController::class,'get']);
});

//Mixed routes Agent and User
Route::middleware(['auth:api', 'role:agent|user'])->prefix('ticket')->group(function () {
    Route::post('/close/{ticket}',[TicketController::class,'updateStatusToClosed']);
});
