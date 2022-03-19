<?php
namespace App\ScheduleObjects;
use App\Models\Schedule;
use App\Notifications\OverstayNotification;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class OverstayUser
{
    public function __invoke()
    {
        $date = Carbon::now()->setTimezone(config('app.timezone'));
        $time = $date->format('H:i:s');

        $schedules = Schedule::with('batches.user.rfid')->hasScheduleToday()->get();
        $schedulesOverstay = $schedules->filter(function ($value, $key) use ($date) {
            $additional30mins = Carbon::parse($value->end_at)->addMinutes(30);
            return $date->greaterThan($additional30mins);
        });

        $schedulesOverstay->each(function ($schedule, $k) {
            $schedule->batches->each(function ($item, $key){
                if ($item->user->rfid?->is_logged) {
                    $item->user->notify(new OverstayNotification($item->user));
                }
            });
        });
    }
}
