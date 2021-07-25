<?php

use App\Http\Controllers\Auth\RegisteredUserController;
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

Route::get('/dashboard', function () {
    return inertia('Dashboard', [
        'token' => session()->get('authToken'),
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__ . '/auth.php';