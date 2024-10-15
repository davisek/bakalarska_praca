<?php

use App\Http\Controllers\SensorReading\SensorReadingController;
use App\Http\Controllers\Setting\SettingController;
use App\Http\Controllers\User\AuthController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('sensor-readings')->group(function () {
    Route::get('', [SensorReadingController::class, 'show']);
    Route::get('/collection', [SensorReadingController::class, 'index']);
    Route::get('/collection/raw', [SensorReadingController::class, 'getRawData']);
});

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});

Route::prefix('settings')->middleware('auth:sanctum')->group(function () {
    Route::get('', [SettingController::class, 'show']);
    Route::put('', [SettingController::class, 'update'])->middleware('email.verified');
});

Route::prefix('user')->middleware('auth:sanctum')->group(function () {
    Route::get('', [UserController::class, 'show']);
    Route::put('', [UserController::class, 'update']);
});

Route::prefix('meta-data')->group(function () {
    Route::get('', [AuthController::class, 'metaData']);
});
