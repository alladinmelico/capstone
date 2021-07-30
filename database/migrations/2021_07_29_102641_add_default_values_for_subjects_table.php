<?php

use Illuminate\Database\Migrations\Migration;

class AddDefaultValuesForSubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('subjects')->insert([
            [
                'name' => 'Business Intelligence',
                'code' => 'BI',
            ],
            [
                'name' => 'Capstone Project 1',
                'code' => 'CP1',
            ],
            [
                'name' => 'Capstone Project 2',
                'code' => 'CP2',
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

    }
}