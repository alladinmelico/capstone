<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ScheduleRequest;
use App\Http\Requests\ScheduleUpdateRequest;
use App\Http\Resources\ScheduleResource;
use App\Models\Batch;
use App\Models\ClassroomUser;
use App\Models\Schedule;
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
        $batches = array();

        if ($user->role_id !== 1) {
            $batches = Batch::where('user_id', $user->id)->get();
        }

        return ScheduleResource::collection(
            Schedule::when($user->role_id !== 1, function ($query) use ($batches, $user) {
                return $query->whereIn('id', $batches->pluck('schedule_id'))->orWhere('user_id', $user->id);
            })->withCount('batches')->with(['classroom.section', 'facility'])->orderBy('updated_at', 'desc')->paginate($request->limit)
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

        if (!$request->is_recurring){
          $data['start_date'] = $data['end_date'];
        }

        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('attachments', 's3');
            $data['attachment'] = Storage::disk('s3')->url($path);
        } else if (!empty($data['attachment_string'])) {
            $fileName = 'attachments/' . uniqid(preg_replace('/\s+/', '', $data['title'])) . '.pdf';
            Storage::disk('s3')->put($fileName, base64_decode($request->attachment_string));
            $data['attachment'] = Storage::disk('s3')->url($fileName);
        }

        $schedule = Schedule::create($data);

        if (!empty($data['users'])) {
            $schedule->batches()->saveMany($this->formatUsers($data['users']));
            $schedule->batches->each(function ($item, $key) use ($schedule) {
                if ($item->user->is_valid_email) {
                    $item->user->notify(new ScheduleCreated($schedule));
                }
            });
            $classroom = $schedule->classroom;

            if (!empty($classroom) && !empty($classroom->section?->faculty)) {
                if ($classroom->section->faculty->is_valid_email) {
                    $classroom->section->faculty->notify(new ScheduleCreated($schedule));
                }
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

        if (!$request->is_recurring){
          $data['start_date'] = $data['end_date'];
        }

        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('attachments', 's3');
            $data['attachment'] = Storage::disk('s3')->url($path);
        } else if (!empty($data['attachment_string'])) {
            $fileName = 'attachments/' . uniqid(preg_replace('/\s+/', '', $data['title'])) . '.pdf';
            Storage::disk('s3')->put($fileName, base64_decode($request->attachment_string));
            $data['attachment'] = Storage::disk('s3')->url($fileName);
        }

        $schedule->update($data);
        if (!empty($data['users'])) {
            $schedule->batches()->delete();
            $schedule->batches()->saveMany($this->formatUsers($data['users']));
        }
        return new ScheduleResource($schedule->load(['classroom', 'batches', 'facility', 'user']));
    }

    public function approve(Request $request, Schedule $schedule)
    {
        $schedule->remarks = $request->remarks;
        if ($request->approve) {
            $schedule->approved_at = now();
        } else {
            $schedule->approved_at = null;
        }
        $schedule->save();
        return new ScheduleResource($schedule);
    }

    public function destroy(Schedule $schedule)
    {
        return $schedule->delete();
    }

    public function delete($schedule)
    {
        $schedule = Schedule::withTrashed()->findOrFail($schedule);
        $schedule->batches()->delete();
        return $schedule->forceDelete();
    }

    public function restore($schedule)
    {
        return Schedule::withTrashed()->findOrFail($schedule)->restore();
    }

    public function today()
    {
        $date = Carbon::now()->setTimezone(config('app.timezone'));
        $time = $date->format('H:i:s');

        return ScheduleResource::collection(Schedule::hasScheduleToday()
                ->with(['batches.user.rfid'])
                ->get()
                ->filter(function ($value, $key) use ($date) {
                    if ($value->is_recurring) {
                        return $value->valid_recurring;
                    }
                    return true;
                }));
    }

    public function overstayed()
    {
        $date = Carbon::now()->setTimezone(config('app.timezone'));
        $time = $date->format('H:i:s');

        $schedules = Schedule::with('batches.user.rfid')->hasScheduleToday()->get();
        $schedulesOverstay = $schedules->filter(function ($value, $key) use ($date) {
            $additional30mins = Carbon::parse($value->end_at)->addMinutes(30);
            return $date->greaterThan($additional30mins);
        });

        $schedulesOverstay->each(function ($schedule, $k) {
            $schedule->batches = $schedule->batches->filter(function ($item, $key) {
                return $item->user->rfid && $item->user->rfid->is_logged;
            });
        });

        return ScheduleResource::collection($schedulesOverstay);
    }

    public function dashboard()
    {
        $date = Carbon::now()->setTimezone(config('app.timezone'));
        $time = $date->format('H:i:s');

        $schedulesToday = Schedule::hasScheduleToday()
            ->with(['batches.user.rfid'])
            ->get()
            ->filter(function ($value, $key) use ($date) {
                if ($value->is_recurring) {
                    return $value->valid_recurring;
                }
                return true;
            });

        $schedulesNow = $schedulesToday->filter(function ($value, $key) use ($date) {
            return $date->greaterThan($value->start_at) && $date->lessThan($value->end_at);
        });

        $schedulesOverstay = $schedulesNow->values()->filter(function ($value, $key) use ($date) {
            return $date->greaterThan($value->end_at);
        });

        $schedulesOverstay = $schedulesOverstay->pluck('batches')->flatten()->filter(function ($value, $key) {
            return !empty($value->user->rfid) && $value->user->rfid->is_logged === 1;
        })->pluck('user')->unique('id')->values();

        $presentStudents = $schedulesNow->pluck('batches')->flatten()->filter(function ($value, $key) {
            return !empty($value->user->rfid) && $value->user->rfid->is_logged === 1;
        })->pluck('user')->unique('id')->values();

        return [
            'present_students' => $presentStudents,
            'schedules_today' => $schedulesToday->values(),
            'schedules_now' => $schedulesNow->values(),
            'schedules_overstay' => $schedulesOverstay->values(),
        ];
    }
}
