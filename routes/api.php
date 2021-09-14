<?php

use App\Http\Controllers\Api\ClassroomController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\FacilityController;
use App\Http\Controllers\Api\ScheduleController;
use App\Http\Controllers\Api\SubjectController;
use App\Http\Controllers\Api\TemperatureController;
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

Route::middleware('api')->group(function () {
    Route::apiResource('temperature', TemperatureController::class);
    Route::apiResource('schedule', ScheduleController::class);
    Route::apiResource('course', CourseController::class);
    Route::apiResource('classroom', ClassroomController::class);
    Route::apiResource('facility', FacilityController::class);
    Route::apiResource('subject', SubjectController::class);
});
Route::middleware('auth:sanctum')->group(function () {

});