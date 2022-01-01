<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;
use Carbon\Carbon;

class CreateScheduleSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('schedule.sem_ends_on', Carbon::now());
        $this->migrator->add('schedule.include_weekends', true);
    }
}
