<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDefaultValuesToCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('courses')->insert([
            [
                'name' => 'Bachelor of Science in Information Technology',
                'code' => 'BSIT',
                'department_id' => 1,
            ],
            [
                'name' => 'Bachelor of Science in Civil Engineering',
                'code' => 'BSCE',
                'department_id' => 2,
            ],
            [
                'name' => 'Bachelor of Science in Mechanical Engineering',
                'code' => 'BSME',
                'department_id' => 3,
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
        Schema::table('courses', function (Blueprint $table) {
            //
        });
    }
}