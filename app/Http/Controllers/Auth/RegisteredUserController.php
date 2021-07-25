<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'google_id' => 'nullable',
            'avatar' => 'nullable',
            'avatar' => 'nullable',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }

    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->user();

            if (session()->has('authToken')) {
                $user = Socialite::driver('google')->userFromToken(session()->get('authToken'));
            }

            $findUser = User::where('google_id', $user->id)->first();

            if ($findUser) {
                Auth::login($findUser);
                return redirect(RouteServiceProvider::HOME);
            } else {
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'google_id' => $user->id,
                    'avatar' => $user->avatar,
                    'avatar_original' => $user->avatar_original,
                    'password' => encrypt(''),
                ]);

                event(new Registered($newUser));

                Auth::login($newUser);

                session(['authToken' => $user->token]);
                session(['authSecret' => $user->refreshToken]);
                session(['authExpiresIn' => $user->expiresIn]);

                return redirect(RouteServiceProvider::HOME);
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}