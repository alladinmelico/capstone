<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClassroomRequest;
use App\Http\Resources\ClassroomResource;
use App\Models\Classroom;
use App\Models\ClassroomUser;
use App\Models\User;
use App\Notifications\ClassroomCreated;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $classrooms = ClassroomUser::select('classroom_id')->where('user_id', $user->id)->get();

        return ClassroomResource::collection(
                Classroom::when($request->search, function ($query) {
                    return $request->where('name', 'like', '%' . request()->search . '%')
                        ->orWhere('description_heading', 'like', '%' . request()->search . '%')
                        ->orWhere('section', 'like', '%' . request()->search . '%');
                })
                ->when($user->role_id !== 1, function ($query) use ($classrooms) {
                    return $query->whereIn('id', $classrooms);
                })
                ->with(['subject', 'users', 'section'])
                ->withTrashed()
                ->orderBy('updated_at', 'desc')
                ->paginate($request->limit)
        );
    }

    public function store(ClassroomRequest $request)
    {
        $validated = $request->validated();
        $validated['invite_code'] = uniqid();
        $classroom = Classroom::create($validated);

        if (!empty($validated['users'])) {
            $users = User::whereIn('id', $validated['users'])->get();
            $users->each(function ($item, $key) use ($classroom, $user) {
                $item->notify(new ClassroomCreated($classroom));
            });
        }

        return new ClassroomResource($classroom->load('section'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Classroom  $classroom
     * @return \Illuminate\Http\Response
     */
    public function show(Classroom $classroom)
    {
        return new ClassroomResource($classroom->load(['users', 'schedules', 'subject', 'section']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Classroom  $classroom
     * @return \Illuminate\Http\Response
     */
    public function update(ClassroomRequest $request, Classroom $classroom)
    {
        $validated = $request->validated();
        $classroom->update($validated);
        $classroom->users()->sync($validated['users']);

        $user = auth()->user();
        $user->notify(new ClassroomCreated($classroom));
        return new ClassroomResource($classroom->load('section'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Classroom  $classroom
     * @return \Illuminate\Http\Response
     */
    public function destroy(Classroom $classroom)
    {
        return $classroom->delete();
    }

    public function accept(Request $request, User $user)
    {
        $request->validate(['code' => 'required|string']);
        $classroom = Classroom::where('invite_code', $request->code)->firstOrFail();
        $classroom->users()->attach($user);
        response()->json(['success' => 'success'], 200);
    }

}
