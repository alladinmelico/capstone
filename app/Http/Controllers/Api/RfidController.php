<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Rfid;
use App\Models\Schedule;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RfidController extends Controller
{
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

    public function show(Rfid $rfid)
    {
        if ($rfid->is_logged) {
            $rfid->is_logged = false;
            $rfid->save();
            abort(204);
        }

        $date = Carbon::now()->setTimezone(config('app.timezone'));
        $time = $date->format('H:i:s');

        $schedule = Schedule::whereIn('classroom_id', function ($query) use ($rfid) {
                $query->select('classroom_id')->from('classroom_users')->where('user_id', $rfid->user_id);
            })
            ->whereDate('end_date', '>=', $date->toDateString())
            ->whereTime('end_at', '>=', $time)
            ->whereTime('start_at', '<=', $time)
            ->get()
            ->filter(function ($value, $key) use ($date) {
                if ($value->is_recurring) {
                    if ($value->repeat_by !== 'daily' && !str_contains($value->days_of_week, strtolower($date->englishDayOfWeek))) {
                        return false;
                    }
                    return true;
                }
                return $value > 2;
            });

        if (count($schedule) == 0) {
            abort(419);
        }

        $rfid->is_logged = true;
        $rfid->save();
        return response('success', 200);
    }
}
