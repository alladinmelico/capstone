<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ScheduleRequest;
use App\Http\Requests\ScheduleUpdateRequest;
use App\Http\Resources\ScheduleResource;
use App\Models\Batch;
use App\Models\Schedule;
use App\Models\ClassroomUser;
use App\Models\User;
use App\Notifications\ScheduleCreated;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ScheduleController extends Controller
{

    public function index(Request $request)
    {
        $user = auth()->user();
        $classrooms = array();

        if ($user->role_id !== 1) {
            $classrooms = ClassroomUser::select('classroom_id')->where('user_id', $user->id)->get();
        }

        return ScheduleResource::collection(
            Schedule::when($user->role_id !== 1, function ($query) use ($classrooms) {
                return $query->whereIn('classroom_id', $classrooms);
            })->filterAccess()->orderBy('updated_at', 'desc')->paginate($request->limit)
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
        $user = auth()->user();
        $data['user_id'] = $user->id;

        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('attachments', 's3');
            $data['attachment'] = Storage::disk('s3')->url($path);
        } else if (!empty($data['attachment_string'])) {
            $fileName = 'attachments/' . uniqid(preg_replace('/\s+/', '', $data['title'])) . '.pdf';
            Storage::disk('s3')->put($fileName,base64_decode($request->attachment_string));
            $data['attachment'] = Storage::disk('s3')->url($fileName);
        }

        $schedule = Schedule::create($data);

        if (!empty($data['users'])) {
            $schedule->batches()->saveMany($this->formatUsers($data['users']));
            $schedule->batches->each(function ($item, $key) use ($schedule) {
                $item->user->notify(new ScheduleCreated($schedule));
            });
            $classroom = $schedule->classroom;

            if (!empty($classroom) && !empty($classroom->section->faculty)) {
                $classroom->section->faculty->notify(new ScheduleCreated($schedule));
            }
        }

        return new ScheduleResource($schedule->load(['classroom', 'batches', 'user']));
    }

    public function formatUsers($users)
    {
        $usersArr = [];
        for ($i = 0; $i < count($users); $i++) {
            for ($j = 0; $j < count($users[$i]); $j++) {
                $usersArr[] = new Batch([
                    'user_id' => $users[$i][$j],
                    'batch' => $i,
                ]);
            }
        }
        return $usersArr;
    }

    public function show(Schedule $schedule)
    {
        $schedule->load(['classroom', 'batches.user', 'facility', 'user']);
        return new ScheduleResource($schedule);
    }

    public function update(ScheduleUpdateRequest $request, Schedule $schedule)
    {
        $data = $request->validated();

        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('attachments', 's3');
            $data['attachment'] = Storage::disk('s3')->url($path);
        } else if (!empty($data['attachment_string'])) {
            $fileName = 'attachments/' . uniqid(preg_replace('/\s+/', '', $data['title'])) . '.pdf';
            Storage::disk('s3')->put($fileName,base64_decode($request->attachment_string));
            $data['attachment'] = Storage::disk('s3')->url($fileName);
        }

        $schedule->update($data);
        if (!empty($data['users'])) {
            $schedule->batches()->delete();
            $schedule->batches()->saveMany($this->formatUsers($data['users']));
        }
        return new ScheduleResource($schedule->load(['classroom', 'batches', 'facility', 'user']));
    }

    public function destroy(Schedule $schedule)
    {
        return $schedule->delete();
    }

    public function restore(Schedule $schedule)
    {
        return new ScheduleResource($schedule->update(['deleted_at' => ''])->load('classroom'));
    }

    public function today()
    {
        $date = Carbon::now()->setTimezone(config('app.timezone'));
        $time = $date->format('H:i:s');

        return ScheduleResource::collection(Schedule::hasScheduleToday()
                ->with(['classroom.users.rfid'])
                ->get()
                ->filter(function ($value, $key) use ($date) {
                    if ($value->is_recurring) {
                        if ($value->repeat_by !== 'daily' && !str_contains(json_encode($value->days_of_week), strtolower($date->englishDayOfWeek))) {
                            return false;
                        }
                        return true;
                    }
                    return $value > 2;
                }));
    }

    public function dashboard()
    {
        $date = Carbon::now()->setTimezone(config('app.timezone'));
        $time = $date->format('H:i:s');

        $schedulesToday = Schedule::hasScheduleToday()
            ->with(['classroom.users.rfid'])
            ->get()
            ->filter(function ($value, $key) use ($date) {
                if ($value->is_recurring) {
                    if ($value->repeat_by !== 'daily' && !str_contains(json_encode($value->days_of_week), strtolower($date->englishDayOfWeek))) {
                        return false;
                    }
                    return true;
                }
                return $value > 2;
            });

        $schedulesNow = $schedulesToday->filter(function ($value, $key) use ($date) {
            return $date->greaterThan($value->start_at) && $date->lessThan($value->end_at);
        });

        $schedulesOverstay = $schedulesToday->filter(function ($value, $key) use ($date) {
            return $date->greaterThan($value->end_at);
        });

        $countUsers = $schedulesToday->pluck('classroom.users')->flatten()->count();
        $presentStudents = $schedulesNow->pluck('classroom.users')->flatten()->filter(function ($value, $key) {
            return !empty($value->rfid) && $value->rfid->is_logged === 1;
        })->count();

        return [
            'present_students' => $presentStudents,
            'schedules_today' => $schedulesToday->values(),
            'schedules_now' => $schedulesNow->values(),
            'schedules_overstay' => $schedulesOverstay->values(),
        ];
    }
}
