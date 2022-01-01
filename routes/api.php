<?php

use App\Http\Controllers\Api\ClassroomController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\FacilityController;
use App\Http\Controllers\Api\RfidController;
use App\Http\Controllers\Api\ScheduleController;
use App\Http\Controllers\Api\SubjectController;
use App\Http\Controllers\Api\TemperatureController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('schedule', ScheduleController::class);
    Route::apiResource('course', CourseController::class);
    Route::apiResource('classroom', ClassroomController::class);
    Route::apiResource('facility', FacilityController::class);
    Route::apiResource('subject', SubjectController::class);
    Route::apiResource('user', UserController::class);
});

Route::middleware('raspberry')->group(function () {
    Route::apiResource('temperature', TemperatureController::class);
    Route::post('rfid', [RfidController::class, 'store']);
    Route::get('rfid/{rfid:value}', [RfidController::class, 'show']);
});

Route::middleware('api')->group(function () {
    Route::post('/auth', [RegisteredUserController::class, 'handleGoogleSignIn']);
    Route::post('/auth-code', [RegisteredUserController::class, 'handleCode']);
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
                ->middleware('guest');
});
