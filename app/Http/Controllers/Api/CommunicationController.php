<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\AdminReportCreated;
use App\Notifications\NewPolicyUpdate;
use App\Notifications\UserBypassCreated;
use Illuminate\Http\Request;

class CommunicationController extends Controller
{
    public function report(Request $request)
    {
        $data = $request->validate([
            'reason' => 'required|string|max:255',
            'message' => 'nullable|string',
        ]);
        $users = User::isValidEmail()->where('role_id', 1)->get();
        $users->each(function ($value, $key) use ($data) {
            if ($value->is_valid_email) {
                $value->notify(new AdminReportCreated($data['reason'], $data['message']));
            }
        });

        response()->json(['success' => 'success'], 200);
    }

    public function bypass(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'reason' => 'required|string|max:255',
            'message' => 'nullable|string',
        ]);
        $user = User::find($data['user_id']);
        $users = User::isValidEmail()->where('role_id', 1)->get();
        $users->each(function ($value, $key) use ($user, $data) {
            if ($value->is_valid_email) {
                $value->notify(new UserBypassCreated($user, $data['reason'], $data['message']));
            }
        });

        response()->json(['success' => 'success'], 200);
    }

    public function policy(Request $request)
    {
        $users = User::isValidEmail()->get();
        $users->each(function ($value, $key) {
            $value->notify(new NewPolicyUpdate());
        });

        response()->json(['success' => 'success'], 200);
    }
}
