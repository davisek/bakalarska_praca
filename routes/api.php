<?php

use App\Http\Controllers\Log\LogController;
use App\Http\Controllers\Sensor\SensorController;
use App\Http\Controllers\SensorGroup\SensorGroupController;
use App\Http\Controllers\SensorReading\SensorReadingController;
use App\Http\Controllers\Setting\SettingController;
use App\Http\Controllers\User\AuthController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('sensor-readings')->group(function () {
    Route::get('{sensor}', [SensorReadingController::class, 'show']);
    Route::get('/collection/{sensor}', [SensorReadingController::class, 'index']);
    Route::get('/collection/{sensor}/raw', [SensorReadingController::class, 'getRawData']);
    Route::get('/collection/{sensor}/download', [SensorReadingController::class, 'downloadCsv']);
    Route::post('', [SensorReadingController::class, 'create'])->middleware('sensor.admin.key');
});

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::get('locale', [AuthController::class, 'metaData']);
    Route::post('forgot-password', [AuthController::class, 'forgot']);
    Route::post('forgot-password/resend', [AuthController::class, 'resend']);
    Route::post('forgot-password/reset', [AuthController::class, 'reset']);

    Route::group(['middleware' => ['jwt.verify']], function() {
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('resend-code', [AuthController::class, 'resendVerificationCode']);
        Route::post('verify-email', [AuthController::class, 'verifyEmail']);
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

    Route::middleware('is.admin')->group(function () {
        Route::get('/users', [UserController::class, 'index']);
        Route::delete('/users/{userId}', [UserController::class, 'delete']);
        Route::post('/admin', [UserController::class, 'generateAuthKey']);
        Route::get('/admin/statistics', [UserController::class, 'getStatistics']);
    });
});

Route::prefix('sensor-groups')->group(function () {
    Route::get('', [SensorGroupController::class, 'index']);
    Route::get('{sensorGroupId}', [SensorGroupController::class, 'show']);

    Route::group(['middleware' => ['jwt.verify', 'is.admin']], function() {
        Route::post('{sensorGroupId}', [SensorGroupController::class, 'update']);
        Route::delete('{sensorGroupId}', [SensorGroupController::class, 'delete']);
        Route::post('', [SensorGroupController::class, 'create']);
    });
});

Route::prefix('sensors')->group(function () {
    Route::get('{sensorId}', [SensorController::class, 'show']);
    Route::group(['middleware' => ['jwt.verify', 'is.admin']], function() {
        Route::post('{sensorId}', [SensorController::class, 'update']);
        Route::delete('{sensorId}', [SensorController::class, 'delete']);
        Route::post('', [SensorController::class, 'create']);
    });
});

Route::prefix('logs')->group(function () {
    Route::post('', [LogController::class, 'create']);
    Route::get('', [LogController::class, 'index'])->middleware('is.admin');
});
