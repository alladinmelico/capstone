<?php

namespace App\Listeners;

use App\Events\ScheduleCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendScheduleNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ScheduleCreated  $event
     * @return void
     */
    public function handle(ScheduleCreated $event)
    {
        //
    }
}
