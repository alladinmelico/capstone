<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ClassroomResource;
use App\Notifications\ClassroomCreated;
use App\Models\Classroom;
use App\Http\Requests\ClassroomRequest;
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
        return ClassroomResource::collection(Classroom::where('name', 'like', '%'. request()->search . '%')
            ->orWhere('description_heading', 'like', '%'. request()->search . '%')
            ->orWhere('section', 'like', '%'. request()->search . '%')
            ->with('subject')
            ->withTrashed()
            ->paginate($request->limit)
        );
    }

    public function store(ClassroomRequest $request)
    {
        $validated = $request->validated();
        $validated['invite_code'] = uniqid();
        $classroom = Classroom::create($validated);

        if (!empty($validated['users'])) {
            $classroom->users()->sync($validated['users']);
        }

        $user = auth()->user();
        $user->notify(new ClassroomCreated($classroom));
        return new ClassroomResource($classroom);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Classroom  $classroom
     * @return \Illuminate\Http\Response
     */
    public function show(Classroom $classroom)
    {
        return new ClassroomResource($classroom->load(['users', 'schedules', 'subject']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Classroom  $classroom
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Classroom $classroom)
    {
        $validated = $request->validated();
        $classroom = Classroom::update($validated);
        $classroom->users()->sync($validated['users']);

        $user = auth()->user();
        $user->notify(new ClassroomCreated($classroom));
        return new ClassroomResource($classroom);
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
}
