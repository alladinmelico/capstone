<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FacilityResource;
use App\Http\Requests\FacilityRequest;
use App\Models\Facility;
use Illuminate\Http\Request;

class FacilityController extends Controller
{

    public function index(Request $request)
    {
        return FacilityResource::collection(
            Facility::where('type', $request->type && 1)->get()
        );
    }

    public function store(FacilityRequest $request)
    {
        return new FacilityResource(Facility::create($request->validated()));
    }

    public function show(Facility $facility)
    {
        return new FacilityResource($facility);
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
