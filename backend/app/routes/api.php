<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TimeEntryController;
use App\Http\Controllers\StatsController;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->get('/projects', [ProjectController::class, 'index']);
Route::middleware('auth:sanctum')->get('/me', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/projects', [ProjectController::class, 'store']);
    Route::post('/time/start', [TimeEntryController::class, 'start']);
    Route::post('/time/stop', [TimeEntryController::class, 'stop']);
    Route::get('/stats', [StatsController::class, 'index']);
    Route::get('/time/active', [TimeEntryController::class, 'active']);
    Route::get('/stats/daily', [StatsController::class, 'daily']);
    Route::get('/stats/earnings', [StatsController::class, 'earningsDaily']);
    Route::get('/stats/projects', [StatsController::class, 'projectBreakdown']);
    Route::put('/projects/{id}', [ProjectController::class, 'update']);
    Route::delete('/projects/{id}', [ProjectController::class, 'destroy']);
});


