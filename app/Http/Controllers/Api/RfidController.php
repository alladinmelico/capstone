<?php

namespace App\Http\Controllers\Api;

use App\Events\UserLogging;
use App\Http\Controllers\Controller;
use App\Http\Requests\RfidRequest;
use App\Http\Resources\RfidResource;
use App\Models\Rfid;
use App\Models\Schedule;
use App\Models\Temperature;
use App\Models\User;
use Illuminate\Http\Request;

class RfidController extends Controller
{
    public function index(Request $request)
    {
        return RfidResource::collection(Rfid::with(['user'])->orderBy('updated_at', 'desc')->paginate($request->limit));
    }

    public function store(RfidRequest $request)
    {
        return new RfidResource(Rfid::create($request->validated()));
    }

    public function show(Rfid $rfid)
    {
        return new RfidResource($rfid->load('user'));
    }

    public function update(RfidRequest $request, Rfid $rfid)
    {
        $rfid->update($request->validated());
        return new RfidResource($rfid);
    }

    public function destroy(Rfid $rfid)
    {
        return $rfid->delete();
    }

    public function log(Rfid $rfid)
    {
        $temp = Temperature::orderBy('updated_at', 'desc')->firstOrFail();
        if ($temp->temperature < 37.5) {
            if ($rfid->is_logged) {
                $rfid->is_logged = false;
                $rfid->save();
                UserLogging::dispatch($rfid->load('user'));
                abort(204);
            }

            $rfid->load('user');

            $schedules = Schedule::hasSchedule($rfid->user_id);

            if (count($schedules) == 0 && !($rfid->user->role_id === 2 || $rfid->user->role_id === 6)) {
                abort(419);
            }

            $rfid->is_logged = true;
            $rfid->save();
            $rfid->schedules = $schedules;

            UserLogging::dispatch($rfid);

            return [
                'id' => $rfid->user_id,
                'name' => $rfid->user->name,
                'photo' => $rfid->user->avatar_original,
                'school_id' => $rfid->user->school_id
            ];
        }
        return response()->json([], 200);
    }
}
