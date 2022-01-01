<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ScheduleResource;
use App\Models\Classroom;
use App\Models\Facility;
use App\Models\Schedule;
use App\Models\Subject;
use App\Models\User;
use App\Http\Requests\ScheduleRequest;
use App\Notifications\ScheduleCreated;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ScheduleController extends Controller
{

    public function index(Request $request)
    {
        $schedules = User::with('classrooms.schedule')
            ->where('id', $request->user_id)
            ->get()
            ->pluck('classrooms')
            ->flatten()
            ->pluck('schedule');
        return ScheduleResource::collection($schedules);
    }

    public function store(ScheduleRequest $request)
    {
        return new ScheduleResource(Schedule::create($request->validated())->load('classroom'));
    }

    public function show(Schedule $schedule)
    {
        return new ScheduleResource($schedule);
    }

    public function update(ScheduleRequest $request, Schedule $schedule)
    {
        $schedule->update($request->validated());
        return new ScheduleResource($schedule->load('classroom'));
    }

    public function destroy(Schedule $schedule)
    {
        return $schedule->delete();
    }

    public function restore(Schedule $schedule)
    {
        return new ScheduleResource($schedule->update(['deleted_at' => ''])->load('classroom'));
    }
}
