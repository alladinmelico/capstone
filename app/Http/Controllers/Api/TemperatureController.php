<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TemperatureRequest;
use App\Http\Resources\TemperatureResource;
use App\Models\Temperature;
use Illuminate\Http\Request;
use App\Events\UserTemperature;

class TemperatureController extends Controller
{

    public function index(Request $request)
    {
        return TemperatureResource::collection(Temperature::with('user')->orderBy('updated_at', 'desc')->paginate($request->limit));
    }

    public function store(TemperatureRequest $request)
    {
        $temperature = Temperature::create($request->validated());
        UserTemperature::dispatch($temperature->load('user'));
        return new TemperatureResource($temperature);
    }

    public function show(Temperature $temperature)
    {
        return new TemperatureResource($temperature->load('user'));
    }

    public function destroy(Temperature $temperature)
    {
        return $temperature->delete();
    }
}
