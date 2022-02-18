<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ScheduleResource;
use App\Models\Classroom;
use App\Models\Facility;
use App\Models\Schedule;
use App\Models\Subject;
use App\Models\User;
use App\Notifications\ScheduleCreated;
use App\Http\Requests\ScheduleRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ScheduleController extends Controller
{

    public function index(Request $request)
    {
        $userId = $request->user_id;

        return ScheduleResource::collection(
            Schedule::when($userId, function ($query) use ($userId) {
                return whereIn('classroom_id', function ($query) use ($userId) {
                    $query->select('classroom_id')->from('classroom_users')->where('user_id', $userId);
                });
            })->paginate($request->limit)
        );
    }

    public function users(Request $request)
    {
        $schedules = User::with('classrooms.schedule')
            ->where('id', $request->user_id)
            ->paginate($request->limit)
            ->pluck('classrooms')
            ->flatten()
            ->pluck('schedule');
        return ScheduleResource::collection($schedules);
    }

    public function store(ScheduleRequest $request)
    {
        $data = $request->validated();

        if (!empty($data['users'])) {
            // TODO: Batches
        }

        if($request->hasFile('attachment'))
        {
            $path = $request->file('attachment')->store('attachments', 's3');
            $data['attachment'] = Storage::disk('s3')->url($path);
        }

        $schedule = Schedule::create($data);

        $user = User::find($data['user_id']);
        $user->notify(new ScheduleCreated($schedule));

        return new ScheduleResource($schedule->load('classroom'));
    }

    public function show(Schedule $schedule)
    {
        $schedule->load(['classroom']);
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
