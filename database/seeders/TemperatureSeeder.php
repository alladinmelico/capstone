<?php

namespace Database\Seeders;

use App\Models\Temperature;
use App\Models\User;
use Illuminate\Database\Seeder;

class TemperatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Temperature::factory()
            ->for(User::inRandomOrder()->first())
            ->times(40)
            ->create();
    }
}