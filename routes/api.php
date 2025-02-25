<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('auth')->group(function () {
        Route::post('/login', [AuthController::class, 'login'])->name('login');
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::put('/{id}', [AuthController::class, 'update']);
        Route::delete('/{id}', [AuthController::class, 'delete']);
       
    Route::prefix('doctor')->group(function () {
        Route::put('/{id}', [DoctorController::class, 'update']);
        Route::delete('/{id}', [DoctorController::class, 'delete']);
    });
});

//SEM AUTENTICAÇÂO
Route::prefix('doctor')->group(function () {
    Route::post('/', [DoctorController::class, 'create']);
    Route::get('/all', [DoctorController::class, 'indexAll']);
    Route::get('/{id}', [DoctorController::class, 'index']);
});

Route::prefix('user')->group(function () {
    Route::post('/', [UserController::class, 'create']);
    Route::get('/all', [UserController::class, 'indexAll']);
    Route::get('/{id}', [UserController::class, 'index']);
});