<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rfid;

class RfidSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Rfid::factory()->count(200)->create();
    }
}
