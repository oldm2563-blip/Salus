<?php

use App\Http\Controllers\api\AppointmentController;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\DoctorController;
use App\Http\Controllers\api\RespondController;
use App\Http\Controllers\api\SymptomController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/me', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('/symptoms', SymptomController::class)->middleware('auth:sanctum');


Route::get('doctors', [DoctorController::class, 'index'])->middleware('auth:sanctum');
Route::get('doctors/search', [DoctorController::class, 'search'])->middleware('auth:sanctum');
Route::get('doctors/{id}', [DoctorController::class, 'show'])->middleware('auth:sanctum');



Route::apiResource('appointments', AppointmentController::class)->middleware('auth:sanctum');


Route::post('ai/health-advice', [RespondController::class, 'ai_respond'])->middleware('auth:sanctum');
Route::get('ai/history', [RespondController::class, 'history'])->middleware('auth:sanctum');