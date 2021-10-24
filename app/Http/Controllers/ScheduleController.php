<?php

namespace App\Http\Controllers;

use App\Http\Requests\ScheduleRequest;
use App\Models\Classroom;
use App\Models\Facility;
use App\Models\Schedule;
use App\Models\Subject;
use App\Models\User;
use App\Notifications\ScheduleCreated;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return inertia('Schedule/Index', ['items' => Schedule::withTrashed()->get()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = auth()->user();

        return inertia('Schedule/Form', [
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
    public function store(ScheduleRequest $request)
    {
        $validated = $request->validated();
        $schedule = Schedule::create($validated);
        if (!empty($validated['google_classroom_id'])) {
            $schedule->classrooms()->create([
                'name' => $validated['name'],
                'description_heading' => $validated['description_heading'],
                'description' => $validated['description'],
                'section' => $validated['section'],
                'google_classroom_id' => $validated['google_classroom_id'],
                'subject_id' => $validated['subject_id'],
                'schedule_id' => $schedule->id,
            ]);
        }
        $user = User::find($validated['user_id']);
        $user->notify(new ScheduleCreated($schedule));
        return redirect()->route('schedule.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function show(Schedule $schedule)
    {
        return inertia('Schedule/Show', ['item' => $schedule]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function edit(Schedule $schedule)
    {
        return inertia('Schedule/Form', [
            'item' => $schedule,
            'facilities' => Facility::select('id', 'name')->get(),
            'subjects' => Subject::select('id', 'name')->get(),
            'existing_classrooms' => Classroom::select('google_classroom_id')->get(),
            'schedule_classroom' => Classroom::where('schedule_id', $schedule->id)->select('google_classroom_id', 'subject_id')->first(),
            'token' => session()->get('authToken'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function update(ScheduleRequest $request, Schedule $schedule)
    {
        $validated = $request->validated();
        $schedule->update($validated);
        $classroom = Classroom::where('schedule_id', $schedule->id)->first();
        if (empty($classroom)) {
            $schedule->classrooms()->create([
                'google_classroom_id' => $validated['google_classroom_id'],
                'subject_id' => $validated['subject_id'],
                'schedule_id' => $schedule->id,
            ]);
        } else {
            $classroom->google_classroom_id = $validated['google_classroom_id'];
            $classroom->subject_id = $validated['subject_id'];
            $classroom->save();
        }
        return redirect()->route('schedule.show', ['schedule' => $schedule->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function destroy(Schedule $schedule)
    {
        $schedule->delete();
        return redirect()->back();
    }

    public function restore(Schedule $schedule)
    {
        $schedule->restore();
        return redirect()->back();
    }

    protected function rules(): array
    {
        return [
            'start_at' => 'required|string',
            'end_at' => 'required|string',
            'day' => 'required|numeric',
        ];
    }
}