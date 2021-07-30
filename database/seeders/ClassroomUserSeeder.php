<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ClassroomUser;
use App\Models\User;

class ClassroomUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ClassroomUser::factory()
        ->for(User::factory()->create());
    }
}
