<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\TemperatureController;
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
            ]);
        })->name('dashboard');
        Route::resource('temperature', TemperatureController::class);
        Route::group(['middleware' => 'admin', 'prefix' => 'admin', 'as' => 'admin.'], function () {
            Route::resource('course', CourseController::class)->except('index', 'show');
        });
        Route::resource('course', CourseController::class)->only('index', 'show');
    });

    Route::get('/profile-registration', [AuthenticatedSessionController::class, 'profile'])
        ->name('profile-registration');

    Route::post('/profile-registration', [AuthenticatedSessionController::class, 'storeProfile'])
        ->name('store-profile');
});

require __DIR__ . '/auth.php';
