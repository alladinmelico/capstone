<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\ClassroomUser;
use App\Models\User;
use App\Notifications\OverStayNotification;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function index(Request $request)
    {
        $classrooms = array();
        if (!empty($request->classroom_id)) {
            $classrooms = ClassroomUser::select('user_id')->where('classroom_id', $request->classroom_id)->get();
        }

        return UserResource::collection(
            User::when($request->role, function ($query) use ($request) {
                return $query->where('role_id', $request->role);
            })
                ->when(!empty($request->classroom_id), function ($query) use ($request, $classrooms) {
                    return $query->whereIn('id', $classrooms);
                })
                ->with(['course', 'section'])
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
        $user->changes_verified = true;
        $user->verified_by = auth()->user()->id;
        $user->save();
        return new UserResource($user);
    }

    public function destroy(User $user)
    {
        return $user->delete();
    }

    public function overstay(Request $request, User $user)
    {
        $user->notify(new OverStayNotification($user));
        response()->json(['success' => 'success'], 200);
    }
}
