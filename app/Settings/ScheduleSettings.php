<?php

use Spatie\LaravelSettings\Settings;
use Carbon\Carbon;

class ScheduleSettings extends Settings
{
    public Carbon $sem_ends_on;

    public bool $include_weekends;

    public static function group(): string
    {
        return 'schedule';
    }
}
