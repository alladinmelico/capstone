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
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
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
        $user = Socialite::driver('google')->stateless()->user();

        // if (session()->has('authToken')) {
        //     $user = Socialite::driver('google')->userFromToken(session()->get('authToken'));
        // }

        $findUser = User::where('google_id', $user->id)->first();

        session(['authToken' => $user->token]);
        session(['authSecret' => $user->refreshToken]);
        session(['authExpiresIn' => $user->expiresIn]);

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
            return redirect()->route('profile-registration');
        }
    }

    public function handleGoogleSignIn(Request $request)
    {
        $findUser = User::where('google_id', $request->google_id)->first();

        if (empty($findUser)) {
            $data = $request->all();

            $validator = Validator::make($data, [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email',
                'google_id' => 'required|max:255',
                'avatar' => 'required|url',
                'avatar_original' => 'required|url',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            if (str_contains(config('constants.admins'), $data['email']) || config('constants.all_admin')) {
                $data['role_id'] = 1;
            }

            $newUser = User::create($data);

            event(new Registered($newUser));

            return response()->json([
                'id' => $newUser->id,
                'hasProfile' => $newUser->role_id === 1,
                'role_id' => $newUser->role_id,
                'token' => $newUser->createToken('SSCsystem')->plainTextToken,
            ], 201);
        }

        return response()->json([
            'id' => $findUser->id,
            'hasProfile' => (bool)$findUser->school_id,
            'role_id' => $findUser->role_id,
            'token' => $findUser->createToken('SSCsystem')->plainTextToken,
        ], 200);
    }

    public function handleCode(Request $request)
    {
        $response = Http::post('https://oauth2.googleapis.com/token', [
            'client_id' => config('constants.google_client_id'),
            'client_secret' => config('constants.google_secret'),
            'code' => $request->code,
            'redirect_uri' => config('constants.google_redirect'),
            'grant_type' => 'authorization_code',
        ]);

        return $response->json();
    }

}
