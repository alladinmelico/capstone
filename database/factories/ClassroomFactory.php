<?php

namespace Database\Factories;

use App\Models\Classroom;
use App\Models\Schedule;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClassroomFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Classroom::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'subject_id' => Subject::inRandomOrder()->first()->id,
            'schedule_id' => Schedule::inRandomOrder()->first()->id,
        ];
    }
}