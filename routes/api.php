<?php

use App\Http\Controllers\Sensor\SensorController;
use App\Http\Controllers\SensorGroup\SensorGroupController;
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
    Route::get('{sensor}', [SensorReadingController::class, 'show']);
    Route::get('/collection/{sensor}', [SensorReadingController::class, 'index']);
    Route::get('/collection/{sensor}/raw', [SensorReadingController::class, 'getRawData']);
    Route::post('', [SensorReadingController::class, 'store']);
});

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::group(['middleware' => ['jwt.verify']], function() {
        Route::post('/refresh', [AuthController::class, 'refresh']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/resend-code', [AuthController::class, 'resendVerificationCode']);
        Route::post('/verify-email', [AuthController::class, 'verifyEmail']);
    });
});

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::prefix('settings')->group(function () {
        Route::get('', [SettingController::class, 'show']);
        Route::put('', [SettingController::class, 'update'])->middleware('email.verified');
    });

    Route::prefix('user')->group(function () {
        Route::get('', [UserController::class, 'show']);
        Route::put('', [UserController::class, 'update']);
        Route::put('/change-password', [UserController::class, 'changePassword']);
    });
});

Route::prefix('sensor-groups')->group(function () {
    Route::get('', [SensorGroupController::class, 'index']);
    Route::get('meta-data', [SensorGroupController::class, 'metaData']);
    Route::get('{sensorGroupId}', [SensorGroupController::class, 'show']);

    Route::group(['middleware' => ['jwt.verify', 'jwt.refresh']], function() {
        Route::post('{sensorGroupId}', [SensorGroupController::class, 'update']);
    });
});

Route::prefix('sensors')->group(function () {
    Route::get('{sensorId}', [SensorController::class, 'show']);
    Route::group(['middleware' => ['jwt.verify', 'jwt.refresh']], function() {
        Route::post('{sensorId}', [SensorController::class, 'update']);
    });
});
