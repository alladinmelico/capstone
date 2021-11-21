<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Rfid;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RfidController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'value' => 'required|string|max:255|unique:rfids,value',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        return Rfid::create($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Rfid  $rfid
     * @return \Illuminate\Http\Response
     */
    public function show(Rfid $rfid)
    {
        if ($rfid->is_logged) {
            $rfid->is_logged = false;
            $rfid->save();
            abort(204);
        }

        $date = Carbon::now()->setTimezone(config('app.timezone'));
        $time = $date->format('H:i:s');

        $schedule = User::with(['classrooms.schedule' => function ($query) use ($date, $time) {
            $query->whereDate('valid_until', '>=', $date->toDateString())
                ->where('day', strtolower($date->englishDayOfWeek))
                ->whereTime('end_at', '>=', $time)
                ->whereTime('start_at', '<=', $time);
        }])
            ->where('id', $rfid->user_id)
            ->get()
            ->pluck('classrooms')
            ->flatten()
            ->pluck('schedule');

        if (count($schedule) == 0) {
            abort(419);
        }

        $rfid->is_logged = true;
        $rfid->save();
        return response('success', 200);
    }
}