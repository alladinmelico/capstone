<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RfidController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\TemperatureController;
use App\Http\Controllers\UserController;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::get('/auth/redirect', function () {
    $scopes = [
        'https://www.googleapis.com/auth/classroom.courses.readonly',
        'https://www.googleapis.com/auth/classroom.rosters.readonly',
    ];
    return Socialite::driver('google')->scopes($scopes)->redirect();
})->name('oauth');

Route::get('/auth/callback', [RegisteredUserController::class, 'handleGoogleCallback']);

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware('auth')->group(function () {

    Route::middleware('hasProfile')->group(function () {
        Route::get('/dashboard', function () {
            return inertia('Dashboard', [
                'token' => session()->get('authToken'),
                'number_of_schedules' => Schedule::all()->count(),
                'number_of_users' => User::all()->count(),
            ]);
        })->name('dashboard');
        Route::resource('temperature', TemperatureController::class);
        Route::group(['middleware' => 'admin', 'prefix' => 'admin', 'as' => 'admin.'], function () {
            Route::resource('user', UserController::class)->except('show');
            Route::resource('course', CourseController::class)->except('index', 'show');
            Route::resource('rfid', RfidController::class)->except('show');
            Route::resource('section', SectionController::class)->except('show');
            Route::get('user-requests', [UserController::class, 'userRequests'])->name('user-requests');
            Route::post('user-approve/{user}', [UserController::class, 'userApprove'])->name('user-approve');
        });
        Route::resource('user', UserController::class)->only('show');
        Route::resource('course', CourseController::class)->only('index', 'show');
        Route::resource('rfid', RfidController::class)->only('show');
        Route::put('schedule/{schedule}/restore', [ScheduleController::class, 'restore']);
        Route::resource('schedule', ScheduleController::class);
        Route::resource('classroom', ClassroomController::class);
        Route::get('notifications/{notification}', [NotificationController::class, 'show']);
        Route::delete('notifications/{notification}', [NotificationController::class, 'destroy']);
    });

    Route::get('/profile-registration', [AuthenticatedSessionController::class, 'profile'])
        ->name('profile-registration');

    Route::post('/profile-registration', [AuthenticatedSessionController::class, 'storeProfile'])
        ->name('store-profile');
});

require __DIR__ . '/auth.php';