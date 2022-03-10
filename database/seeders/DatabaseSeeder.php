<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            SubjectSeeder::class,
            CourseSeeder::class,
            SectionSeeder::class,
            UserSeeder::class,
            ClassroomSeeder::class,
            TemperatureSeeder::class,
            ScheduleSeeder::class,
            RfidSeeder::class,
            FacilitySeeder::class,
        ]);
    }
}
