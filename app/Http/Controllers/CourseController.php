<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Schedule;
use App\Notifications\ScheduleCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return inertia('Course/Index', ['items' => Course::where('name', 'like', '%'. $request->search . '%')
            ->orWhere('code', 'like', '%'. $request->search . '%')
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
        return inertia('Course/Form', ['departments' => Config::get('constants.departments')]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());
        $course = Course::create($validated);
        // broadcast(new CourseCreated($course))->toOthers();
        $user = Auth::user();
        $user->notify(new ScheduleCreated(Schedule::find(22)));
        return redirect()->route('course.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function show(Course $course)
    {
        return inertia('Course/Show', ['item' => $course]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function edit(Course $course)
    {
        return inertia('Course/Form', ['item' => $course, 'departments' => Config::get('constants.departments')]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Course $course)
    {
        $validated = $request->validate($this->rules());
        $course->update($validated);
        return redirect()->route('course.show', ['course' => $course->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function destroy(Course $course)
    {
        $course->delete();
        return redirect()->back();
    }

    protected function rules(): array
    {
        return [
            'name' => 'required|string',
            'code' => 'required|string',
            'department_id' => 'required|numeric',
        ];
    }
}