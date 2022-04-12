<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TemperatureRequest;
use App\Http\Resources\TemperatureResource;
use App\Models\Temperature;
use App\Models\Rfid;
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
        $data = $request->validated();
        $rfid = Rfid::orderBy('updated_at', 'desc')->firstOrFail();
        if ($rfid->is_logged){
            $data['user_id'] = $rfid->user_id;
            $data['temperature'] = round(floatval($request->temperature), 2);
            $temperature = Temperature::create($data);
            UserTemperature::dispatch($temperature->load('user'));
            return new TemperatureResource($temperature);
        }
        return response()->json([], 200);
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
