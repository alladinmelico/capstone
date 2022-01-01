<?php

namespace App\Providers;

use App\Providers\ClassroomCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendClassroomNotification
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
     * @param  ClassroomCreated  $event
     * @return void
     */
    public function handle(ClassroomCreated $event)
    {
        //
    }
}
