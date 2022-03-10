<?php

namespace Database\Factories;

use App\Models\Classroom;
use App\Models\Section;
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
            'name' => $this->faker->sentence(),
            'description_heading' => $this->faker->sentence(),
            'description' => $this->faker->sentence(),
            'invite_code' => $this->faker->word() . mt_rand(0, 99),
            'subject_id' => Subject::inRandomOrder()->first()->id,
            'section_id' => Section::inRandomOrder()->first()->id,
        ];
    }
}
