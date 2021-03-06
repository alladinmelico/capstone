<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClassroomRequest;
use App\Http\Resources\ClassroomResource;
use App\Models\Classroom;
use App\Models\ClassroomUser;
use App\Models\User;
use App\Models\Section;
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
        $sections = array();
        if ($user->role_id === 2) {
            $sections = Section::where('faculty_id', $user->id)->with('classrooms')->get()->pluck('id');
        }
        return ClassroomResource::collection(
                Classroom::when($request->search, function ($query) {
                    return $request->where('name', 'like', '%' . request()->search . '%')
                        ->orWhere('description_heading', 'like', '%' . request()->search . '%')
                        ->orWhere('section', 'like', '%' . request()->search . '%');
                })
                ->when($user->role_id !== 1, function ($query) use ($classrooms, $sections) {
                    return $query->whereIn('id', $classrooms)->orWhereIn('section_id', $sections);
                })
                ->when(auth()->user()->role_id === 1, function ($query) {
                    return $query->withTrashed();
                })
                ->with(['subject', 'users', 'section.faculty', 'section.president'])
                ->orderBy('updated_at', 'desc')
                ->paginate($request->limit)
        );
    }

    public function store(ClassroomRequest $request)
    {
        $validated = $request->validated();
        $validated['invite_code'] = uniqid();
        $classroom = Classroom::create($validated);

        $section = Section::find($request->section_id);
        $classroom->users()->attach($section->president);
        $classroom->users()->attach($section->faculty);
        $classroom->save();

        if (!empty($validated['users'])) {
            $users = User::whereIn('id', $validated['users'])->get();
            // $classroom->users()->attach($users);
            $users->each(function ($item, $key) use ($classroom) {
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
        $classroom->fill($validated);
        $classroom->users()->sync(array_unique($validated['users']));
        $classroom->save();
        return new ClassroomResource($classroom->load('section', 'users'));
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

    public function delete($classroom)
    {
        $classroom = Classroom::withTrashed()->findOrFail($classroom);
        $classroom->users()->detach();
        return $classroom->forceDelete();
    }

    public function restore($classroom)
    {
        return Classroom::withTrashed()->findOrFail($classroom)->restore();
    }

    public function accept(Request $request, User $user)
    {
        $request->validate(['code' => 'required|string']);
        $classroom = Classroom::with('users')->where('invite_code', $request->code)->firstOrFail();
        $uniqueUsers = $classroom->users->unique('id')->values();

        if (!$uniqueUsers->contains($user)) {
            $uniqueUsers->push($user);
        }
        $classroom->users()->sync($uniqueUsers->pluck('id')->toArray());
        $classroom->save();
        response()->json(['success' => 'success'], 200);
    }

}
