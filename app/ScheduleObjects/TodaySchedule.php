<?php
namespace App\ScheduleObjects;
use App\Models\Schedule;
use App\Notifications\NotifyScheduleToday;
use Illuminate\Support\Facades\Log;

class TodaySchedule
{
    public function __invoke()
    {
        $schedules = Schedule::with('batches.user')->hasScheduleToday()->get();

        $schedules->each(function ($schedule, $k) {
            $schedule->batches->each(function ($item, $key) use ($schedule){
                $item->user->notify(new NotifyScheduleToday($schedule));
            });
        });
    }
}
