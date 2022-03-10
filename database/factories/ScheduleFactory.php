<?php

namespace Database\Factories;

use App\Enums\ScheduleRepeatType;
use App\Enums\ScheduleType;
use App\Models\Classroom;
use App\Models\Facility;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

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
            'title' => $this->faker->sentence(),
            'start_at' => $this->faker->time(),
            'end_at' => $this->faker->time(),
            'start_date' => $this->faker->dateTime(),
            'end_date' => $this->faker->dateTime(),
            'is_recurring' => $this->faker->boolean(),
            'note' => $this->faker->sentence(),
            'type' => Arr::random(ScheduleType::getValues()),
            'repeat_by' => Arr::random(ScheduleRepeatType::getValues()),
            'days_of_week' => $this->faker->randomElements(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'], $count = 3),
            'facility_id' => Facility::inRandomOrder()->first()->id,
            'classroom_id' => Classroom::inRandomOrder()->first()->id,
            'user_id' => User::inRandomOrder()->first()->id,
        ];
    }
}
