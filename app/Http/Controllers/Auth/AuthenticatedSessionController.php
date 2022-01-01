<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Course;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

    public function storeProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|min:3',
            'email' => 'sometimes|email|ends_with:@tup.edu.ph',
            'school_id' => 'required|string|unique:users|max:12|regex:/(TUPT-)\d\d-\d\d\d\d/i',
            'course_id' => 'required|numeric|exists:courses,id',
            'year' => 'required|numeric|between:1,4',
            'section' => 'required|alpha|min:1',
        ]);

        $user = Auth::user();
        $user->changes_verified = false;
        $user->update($validated);

        return redirect(RouteServiceProvider::HOME);
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
