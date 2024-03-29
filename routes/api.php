<?php

use App\Http\Controllers\Api\BatchController;
use App\Http\Controllers\Api\ClassroomController;
use App\Http\Controllers\Api\CommunicationController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\FacilityController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\RfidController;
use App\Http\Controllers\Api\ScheduleController;
use App\Http\Controllers\Api\SectionController;
use App\Http\Controllers\Api\SubjectController;
use App\Http\Controllers\Api\TemperatureController;
use App\Http\Controllers\Api\TicketController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
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
    Route::get('temperature', [TemperatureController::class, 'index']);

    Route::apiResource('facility', FacilityController::class);
    Route::get('availability/facility', [FacilityController::class, 'allAvailable']);
    Route::put('facility/{facility}/restore', [FacilityController::class, 'restore']);
    Route::delete('facility/{facility}/permanently-delete', [FacilityController::class, 'delete']);

    Route::apiResource('subject', SubjectController::class);
    Route::put('subject/{subject}/restore', [SubjectController::class, 'restore']);
    Route::delete('subject/{subject}/permanently-delete', [SubjectController::class, 'delete']);

    Route::group(['middleware' => 'admin'], function () {
        Route::post('bypass', [CommunicationController::class, 'bypass']);
        Route::post('policy', [CommunicationController::class, 'policy']);
        Route::post('overstay/{user}', [UserController::class, 'overstay']);
        Route::get('user-requests', [UserController::class, 'userRequests'])->name('user-requests');
        Route::post('user-approve/{user}', [UserController::class, 'userApprove'])->name('user-approve');
        Route::post('import-temperature', [TemperatureController::class, 'import']);
    });
    Route::apiResource('section', SectionController::class);
    Route::get('me', [UserController::class, 'me']);
    Route::put('user/{user}/verify', [UserController::class, 'verify']);
    Route::apiResource('user', UserController::class);

    Route::apiResource('course', CourseController::class);
    Route::put('course/{course}/restore', [CourseController::class, 'restore']);
    Route::delete('course/{course}/permanently-delete', [CourseController::class, 'delete']);

    Route::apiResource('rfid', RfidController::class);
    Route::post('batch/{batch}/approve', [BatchController::class, 'approve']);
    Route::post('batch/{batch}/attendance', [BatchController::class, 'attendance']);
    Route::post('batch/{batch}/leaveApplication', [BatchController::class, 'leaveApplication']);

    Route::get('dashboard', [ScheduleController::class, 'dashboard']);
    Route::get('today', [ScheduleController::class, 'today']);
    Route::get('overstayed/schedule', [ScheduleController::class, 'overstayed']);
    Route::put('schedule/{schedule}/restore', [ScheduleController::class, 'restore']);
    Route::delete('schedule/{schedule}/permanently-delete', [ScheduleController::class, 'delete']);
    Route::put('schedule/{schedule}/approve', [ScheduleController::class, 'approve'])->name('schedule.approve');
    Route::get('schedule/{schedule}/qr-code', [ScheduleController::class, 'qrCode'])->name('schedule.qr-code');
    Route::apiResource('schedule', ScheduleController::class);

    Route::apiResource('classroom', ClassroomController::class);
    Route::post('classroom/accept/{user}', [ClassroomController::class, 'accept']);
    Route::put('classroom/{classroom}/restore', [ClassroomController::class, 'restore']);
    Route::delete('classroom/{classroom}/permanently-delete', [ClassroomController::class, 'delete']);

    Route::apiResource('ticket', TicketController::class);

    Route::apiResource('rfid', RfidController::class);
    Route::apiResource('temperature', TemperatureController::class)->except('store');
    Route::get('buildings', function () {
        return mapToIdValue(config('constants.buildings'));
    });
    Route::get('facility-types', function () {
        return mapToIdValue(config('constants.facilities.types'));
    });
    Route::get('departments', function () {
        return mapToIdValue(config('constants.departments'));
    });
    Route::group(['prefix' => 'notifications'], function () {
        Route::get('/', [NotificationController::class, 'index']);
        Route::delete('/{notification}', [NotificationController::class, 'destroy']);
        Route::post('/{notification}/read', [NotificationController::class, 'read']);
        Route::post('/{notification}/unread', [NotificationController::class, 'unread']);
        Route::post('/mark-all-read', [NotificationController::class, 'markAllRead']);
        Route::get('/unread-count', [NotificationController::class, 'unreadNotifCount']);
    });

    Route::get('/profile-registration', [AuthenticatedSessionController::class, 'profile'])
        ->name('profile-registration');

    Route::post('/profile-registration', [AuthenticatedSessionController::class, 'storeProfile'])
        ->name('store-profile');
});

Route::middleware('raspberry')->group(function () {
    Route::post('temperature', [TemperatureController::class, 'store']);
    Route::get('rfid/{rfid:value}/log', [RfidController::class, 'log']);
});

Route::middleware('api')->group(function () {
    Route::post('/auth', [RegisteredUserController::class, 'handleGoogleSignIn']);
    Route::post('/auth-code', [RegisteredUserController::class, 'handleCode']);
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
        ->middleware('guest');
});

function mapToIdValue($arr)
{
    return array_map(function ($key, $value) {
        return ['id' => $key, 'value' => $value];
    }, array_keys($arr), $arr);
}
