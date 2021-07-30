<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDefaultValuesToFacilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('facilities')->insert([
            [
                'name' => 'Computer Technology Lab',
                'code' => 'CTL',
                'building_id' => 1,
            ],
            [
                'name' => 'Civil Engineering Lab',
                'code' => 'CEL',
                'building_id' => 2,
            ],
            [
                'name' => 'Mechanical Engineering Lab',
                'code' => 'MEL',
                'building_id' => 3,
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('facilities', function (Blueprint $table) {
            //
        });
    }
}