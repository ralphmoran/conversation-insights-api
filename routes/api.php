<?php

use App\Http\Controllers\Api\ConversationController;
use App\Http\Controllers\Api\MetricsController;
use App\Http\Controllers\Api\SimulationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Conversation API routes
Route::apiResource('conversations', ConversationController::class)->only([
    'index', 'store', 'show', 'destroy'
]);

// Metrics API route
Route::get('/metrics', [MetricsController::class, 'index']);

// Simulation routes (for demo purposes)
Route::post('/simulate', [SimulationController::class, 'generate']);
Route::delete('/simulate/reset', [SimulationController::class, 'reset']);
