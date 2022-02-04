<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TemperatureRequest;
use App\Http\Resources\TemperatureResource;
use App\Models\Temperature;
use Illuminate\Http\Request;

class TemperatureController extends Controller
{

    public function index(Request $request)
    {
        return TemperatureResource::collection(Temperature::paginate($request->limit));
    }

    public function store(TemperatureRequest $request)
    {
        return new TemperatureResource(Temperature::create($request->validated()));
    }

    public function show(Temperature $temperature)
    {
        return new TemperatureResource($temperature);
    }

    public function destroy(Temperature $temperature)
    {
        return $temperature->delete();
    }
}
