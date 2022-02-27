<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ScheduleController;
use Illuminate\Support\Facades\Route;

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

Route::get('/schedule/{schedule}', [ScheduleController::class, 'check']);

Route::get('/sample', function () {
    $pdf = PDF::loadView('reports.temperature');
    return $pdf->download('pdfview.pdf');
    return view('reports.temperature');
});
