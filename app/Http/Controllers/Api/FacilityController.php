<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FacilityRequest;
use App\Http\Resources\FacilityResource;
use App\Models\Facility;
use App\Models\Schedule;
use App\Models\Batch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FacilityController extends Controller
{

    public function index(Request $request)
    {
        if (!empty($request->has_schedule)) {
            // get currently occupied facility
            $schedulesNow = Schedule::hasScheduleNow()->with(['classroom.users', 'batches.user'])->orderBy('updated_at', 'desc')->get();
            $facilities = Facility::with(['schedules' => function ($q) use ($schedulesNow) {
                return $q->whereIn('id', $schedulesNow->pluck('id'));
            }])->orderBy('updated_at', 'desc')->paginate($request->limit);

            $facilities = $facilities->map(function ($facility) use ($schedulesNow) {
                $facility->occupied = ($schedulesNow->firstWhere('facility_id', $facility->id))?->batches->count();
                return $facility;
            });

            return FacilityResource::collection($facilities);
        }

        return FacilityResource::collection(Facility::with(['schedules'])->orderBy('updated_at', 'desc')->paginate($request->limit));
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
            });
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
}
