<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Facility;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return inertia('Classroom/Index', ['items' => Classroom::where('name', 'like', '%'. request()->search . '%')
            ->orWhere('description_heading', 'like', '%'. request()->search . '%')
            ->orWhere('section', 'like', '%'. request()->search . '%')
            ->with('subject')
            ->withTrashed()
            ->paginate(10)]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = auth()->user();

        return inertia('Classroom/Form', [
            'section' => $user->role_id === User::STUDENT ? $user->course->code . '-' . $user->year . strtoupper($user->section) : '',
            'facilities' => Facility::select('id', 'name')->get(),
            'subjects' => Subject::select('id', 'name')->get(),
            'existing_classrooms' => Classroom::select('google_classroom_id')->get(),
            'token' => session()->get('authToken'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Classroom  $classroom
     * @return \Illuminate\Http\Response
     */
    public function show(Classroom $classroom)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Classroom  $classroom
     * @return \Illuminate\Http\Response
     */
    public function edit(Classroom $classroom)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Classroom  $classroom
     * @return \Illuminate\Http\Response
     */
    public function destroy(Classroom $classroom)
    {
        //
    }
}