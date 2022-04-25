<?php
namespace App\ScheduleObjects;
use App\Models\Schedule;
use App\Notifications\NotifyScheduleToday;
use Illuminate\Support\Facades\Log;

class TodaySchedule
{
    public function __invoke()
    {
        $date = Carbon::now()->setTimezone(config('app.timezone'));

        $schedules = Schedule::with('batches.user')
            ->whereDate('end_date', '>=', $date->toDateString())
            ->whereDate('start_date', '<=', $date->toDateString())
            ->get();

        $schedules->each(function ($schedule, $k) {
            $schedule->batches->each(function ($item, $key) use ($schedule){
                $item->user->notify(new NotifyScheduleToday($schedule));
            });
        });
    }
}
