<?php

namespace App\Http\Controllers;

use App\Models\Schedule;

class ScheduleController extends Controller
{
    public function check(Schedule $schedule)
    {
        return view('schedule', ['isValid' => $schedule->is_valid]);
    }
}
