<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function index(Request $request)
    {
        return UserResource::collection(
            User::when($request->role, function ($query) use ($request) {
                    return $query->where('role_id', $request->role);
                })
                ->when($request->classroom_id, function ($query) use ($request) {
                    return $query->whereIn('id', function ($query) use ($request) {
                        $query->select('user_id')->from('classroom_users')->where('classroom_id', $request->classroom_id);
                    });
                })
                ->with(['course'])
                ->orderBy('updated_at', 'desc')
                ->paginate($request->limit)
        );
    }

    public function store(UserRequest $request)
    {
        return new UserResource(User::create($request->validated()));
    }

    public function show(User $user)
    {
        return new UserResource($user->load(['course', 'section']));
    }

    public function update(UserRequest $request, User $user)
    {
        $user->update($request->validated());
        return new UserResource($user);
    }

    public function verify(Request $request, User $user)
    {
        $user->changes_verified = True;
        $user->verified_by = auth()->user()->id;
        $user->save();
        return new UserResource($user);
    }

    public function destroy(User $user)
    {
        return $user->delete();
    }
}
