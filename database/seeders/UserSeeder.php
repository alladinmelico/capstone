<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Section;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()
            ->times(300)
            ->for(Section::factory()->create())
            ->create();
    }
}
