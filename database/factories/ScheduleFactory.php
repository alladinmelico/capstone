<?php

namespace Database\Factories;

use App\Models\Facility;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScheduleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Schedule::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'start_at' => $this->faker->time(),
            'end_at' => $this->faker->time(),
            'day' => strtolower($this->faker->dayOfWeek()),
            'valid_until' => $this->faker->dateTime(),
            'note' => $this->faker->sentence(),
            'facility_id' => Facility::inRandomOrder()->first()->id,
            'user_id' => User::inRandomOrder()->first()->id,
        ];
    }
}