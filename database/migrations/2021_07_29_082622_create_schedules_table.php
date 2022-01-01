<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('type')->default('others');
            $table->time('start_at');
            $table->date('start_date');
            $table->time('end_at');
            $table->date('end_date')->nullable();
            $table->boolean('is_recurring')->default(false);
            $table->string('repeat_by')->nullable();
            $table->json('days_of_week')->nullable();
            $table->string('note')->nullable();
            $table->foreignId('facility_id')->constrained();
            $table->foreignId('classroom_id')->nullable()->constrained();
            $table->foreignId('user_id')->constrained();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schedules');
    }
}
