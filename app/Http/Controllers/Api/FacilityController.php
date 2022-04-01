<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FacilityRequest;
use App\Http\Resources\FacilityResource;
use App\Models\Batch;
use App\Models\Facility;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FacilityController extends Controller
{

    public function index(Request $request)
    {
        if (!empty($request->has_schedule)) {
            // get currently occupied facility
            $schedulesNow = Schedule::hasScheduleNow()->with(['facility', 'batches.user'])->orderBy('updated_at', 'desc')->get();
            $facilities = Facility::with(['schedules' => function ($q) use ($schedulesNow) {
                    return $q->whereIn('id', $schedulesNow->pluck('id'));
                }])
                ->when(auth()->user()->role_id === 1, function ($query) {
                    return $query->withTrashed();
                })
                ->orderBy('updated_at', 'desc')->paginate($request->limit);

            $facilities = $facilities->map(function ($facility) use ($schedulesNow) {
                $facility->occupied = ($schedulesNow->firstWhere('facility_id', $facility->id))?->batches->count();
                return $facility;
            });

            return FacilityResource::collection($facilities);
        }

        return FacilityResource::collection(
            Facility::with(['schedules'])
            ->when(auth()->user()->role_id === 1, function ($query) {
                return $query->withTrashed();
            })
            ->orderBy('updated_at', 'desc')
            ->paginate($request->limit)
        );
    }

    public function allAvailable(Request $request)
    {
        $request->validate([
            'start_time' => 'required|date_format:H:i|before:end_at',
            'start_date' => 'required|date_format:Y-m-d',
            'end_time' => 'required|date_format:H:i|after:start_at',
            'end_date' => 'required|date_format:Y-m-d',
            'type' => 'required'
        ]);
        $facilities = Facility::with('schedules')->orderBy('updated_at', 'desc')->get();

        $facilities = $facilities->filter(function ($facility) use ($request) {
            if (strtolower($facility->type) !== $request->type) {
                return false;
            }
            if ($facility->schedules->count() === 0) {
                return true;
            }
            $startDate = Carbon::create($request->start_date);
            $endDate = Carbon::create($request->end_date);
            $startTime = Carbon::create($request->start_time);
            $endTime = Carbon::create($request->end_time);

            $hasConflictingSchedule = false;

            $val = $facility->schedules->first(function ($schedule)
                 use ($request, $startDate, $endDate, $startTime, $endTime) {
                    if (!($endDate->lessThan(Carbon::create($schedule->start_date)) ||
                        $startDate->greaterThan(Carbon::create($schedule->end_date)))
                        && $schedule->isValidDateRecurring($startDate)) {
                        return $schedule->isBetweenTime($startTime, $endTime);
                    }
                    return false;
                });
            return empty($val);
        });

        return FacilityResource::collection($facilities);
    }

    public function store(FacilityRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('cover')) {
            $path = $request->file('cover')->store('facility_covers', 's3');
            $data['cover'] = Storage::disk('s3')->url($path);
        }
        return new FacilityResource(Facility::create($data));
    }

    public function show(Facility $facility)
    {
        $facility->load('schedules');
        if (auth()->user()->role_id !== 1) {
            $batches = Batch::where('user_id', 17)->get()->pluck('schedule_id');
            $facility->schedules = $facility->schedules->filter(function ($value, $key) use ($batches) {
                return $batches->contains($value->id);
            })->values();
        }
        return new FacilityResource($facility);
    }

    public function update(FacilityRequest $request, Facility $facility)
    {
        $data = $request->validated();

        if ($request->hasFile('cover')) {
            $path = $request->file('cover')->store('facility_covers', 's3');
            $data['cover'] = Storage::disk('s3')->url($path);
        }
        $facility->update($data);
        return new FacilityResource($facility);
    }

    public function destroy(Facility $facility)
    {
        return $facility->delete();
    }

    public function delete($facility)
    {
        $facility = Facility::withTrashed()->findOrFail($facility);
        $facility->schedules->each(function ($item) {
            $item->delete();
        });
        return $facility->forceDelete();
    }

    public function restore($facility)
    {
        return Facility::withTrashed()->findOrFail($facility)->restore();
    }

}
