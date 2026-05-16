<?php 

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\GroupController;
use App\Http\Controllers\Api\MenuController;
use App\Http\Controllers\Api\ParticipantController;
use App\Http\Controllers\Api\RoundController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// ===================
// PUBLIC
// ===================
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/me', fn (Request $r) => $r->user());
    Route::post('/logout', [AuthController::class, 'logout']);

    // MENU (semua role login)
    Route::get('/menus', [MenuController::class, 'index']);

    // ADMIN ONLY
    Route::middleware('role:admin')->group(function () {
        Route::get('/users', [UserController::class, 'index']);
        Route::post('/users', [UserController::class, 'store']);
        Route::put('/users/{id}', [UserController::class, 'update']);
        Route::delete('/users/{id}', [UserController::class, 'destroy']);
    });

    Route::middleware('role:admin,petugas')->group(function () {
        Route::get('/groups', [GroupController::class, 'index']);
        Route::get('/groups_export', [GroupController::class, 'exportExcel']);
        Route::post('/groups', [GroupController::class, 'store']);
        Route::put('/groups/{id}', [GroupController::class, 'update']);
        Route::delete('/groups/{id}', [GroupController::class, 'destroy']);
        Route::get('/participants', [ParticipantController::class, 'index']);
        Route::post('/participants', [ParticipantController::class, 'store']);
        Route::put('/participants/{id}', [ParticipantController::class, 'update']);
        Route::delete('/participants/{id}', [ParticipantController::class, 'destroy']);
        Route::get('/rounds', [RoundController::class, 'index']);
        Route::get('/rounds_fetch_arisan', [RoundController::class, 'get_arisan']);
        Route::get('/round_histori_arisan', [RoundController::class, 'getHistoriArisan']);
        Route::post('/rounds', [RoundController::class, 'store']);
        Route::post('/rounds_payment', [RoundController::class, 'payment']);
        Route::post('/rounds_winner', [RoundController::class, 'winner']);
    });

});

