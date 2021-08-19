<?php

namespace App\Providers;

use App\Events\CourseCreated;
use App\Events\ScheduleCreated;
use App\Listeners\SendCourseNotification;
use App\Listeners\SendScheduleNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        ScheduleCreated::class => [
            SendScheduleNotification::class,
        ],
        CourseCreated::class => [
            SendCourseNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}