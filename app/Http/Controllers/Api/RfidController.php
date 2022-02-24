<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Rfid;
use App\Models\Schedule;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Resources\RfidResource;
use App\Http\Requests\RfidRequest;
use App\Events\UserLogging;

class RfidController extends Controller
{
    public function index(Request $request)
    {
        return RfidResource::collection(Rfid::paginate($request->limit));
    }

    public function store(RfidRequest $request)
    {
        return new RfidResource(Rfid::create($request->validated()));
    }

    public function show(Rfid $rfid)
    {
        return new RfidResource($rfid);
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
        if ($rfid->is_logged) {
            $rfid->is_logged = false;
            $rfid->save();
            UserLogging::dispatch($rfid);
            abort(204);
        }

        $schedule = Schedule::hasSchedule($rfid->user_id);

        if (count($schedule) == 0) {
            abort(419);
        }

        $rfid->is_logged = true;
        $rfid->save();
        $rfid->load('user');

        UserLogging::dispatch($rfid);

        return [
            'id' => $rfid->user_id,
            'name' => $rfid->user->name,
            'photo' => $rfid->user->avatar_original,
            'school_id' => $rfid->user->school_id,
            'start_at' => $schedule->first()->start_at,
            'end_at' => $schedule->first()->end_at,
        ];
    }
}
