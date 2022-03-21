<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FacilityResource;
use App\Http\Requests\FacilityRequest;
use App\Models\Facility;
use App\Models\Schedule;
use Illuminate\Http\Request;

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
        return new FacilityResource(Facility::create($request->validated()));
    }

    public function show(Facility $facility)
    {
        return new FacilityResource($facility->load('schedules'));
    }

    public function update(FacilityRequest $request, Facility $facility)
    {
        $facility->update($request->validated());
        return new FacilityResource($facility);
    }

    public function destroy(Facility $facility)
    {
        return $facility->delete();
    }
}
