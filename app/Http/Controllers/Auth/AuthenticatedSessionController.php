<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Course;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use App\Http\Requests\ProfileRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Enums\UserType;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Display the login view.
     *
     * @return \Inertia\Response
     */
    public function profile()
    {
        return inertia('Auth/Profile', [
            'user' => Auth::user(),
            'courses' => Course::select('id', 'name', 'code')->get(),
        ]);
    }

    public function storeProfile(ProfileRequest $request)
    {
        $validated = $request->validated();

        $user = Auth::user();
        $user->changes_verified = false;
        if (str_contains(config('constants.admins'), $user->email) || config('constants.all_admin')) {
            $user->role_id = UserType::ADMIN;
        }
        $user->update($validated);

        return new UserResource($user->load('course'));
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        return response()->json(['data' => [
            'id' => $user->id,
            'token' => $user->createToken('SSCsystem')->plainTextToken,
        ]], 200);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        session()->forget('authToken');

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
