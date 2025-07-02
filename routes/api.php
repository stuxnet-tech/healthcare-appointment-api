<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\HealthcareProfessionalController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    
    Route::get('/healthcare-professionals', [HealthcareProfessionalController::class, 'index']);
    
    Route::prefix('appointments')->group(function () {
        Route::get('/', [AppointmentController::class, 'index']);
        Route::post('/', [AppointmentController::class, 'store']);
        Route::delete('/{appointment}', [AppointmentController::class, 'destroy']);
        Route::patch('/{appointment}/complete', [AppointmentController::class, 'complete']);
    });
});