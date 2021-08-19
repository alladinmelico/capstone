<?php

namespace App\Listeners;

use App\Events\CourseCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendCourseNotification
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
     * @param  CourseCreated  $event
     * @return void
     */
    public function handle(CourseCreated $event)
    {
        //
    }
}
