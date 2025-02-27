<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('auth')->group(function () {
    Route::middleware('auth.jwt')->group(function () {

    Route::prefix('doctor')->group(function () {
        Route::post('/', [DoctorController::class, 'create']);
        Route::put('/{id}', [DoctorController::class, 'update']);
        Route::delete('/{id}', [DoctorController::class, 'delete']);
        Route::post('/add-agreement', [DoctorController::class, 'addDoctorToAgreement']);
        Route::post('/add-specialty', [DoctorController::class, 'addDoctorToSpecialty']);
    });

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::delete('/{id}', [AuthController::class, 'delete']);
    Route::put('/{id}', [AuthController::class, 'update']);

    Route::prefix('appointment')->group(function () {
        Route::post('/', [AppointmentController::class, 'create']);
    });
    
    });
        Route::post('/login', [AuthController::class, 'login'])->name('login');   
});

Route::prefix('doctor')->group(function () {
    Route::get('/all', [DoctorController::class, 'indexAll']);
    Route::get('/{id}', [DoctorController::class, 'index']);
});

Route::prefix('user')->group(function () {
    Route::post('/', [UserController::class, 'create']);
    Route::get('/all', [UserController::class, 'indexAll']);
    Route::get('/{id}', [UserController::class, 'index']);
});