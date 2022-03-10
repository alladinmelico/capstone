<?php

namespace Database\Factories;

use App\Models\Rfid;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RfidFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Rfid::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'value' => $this->faker->numberBetween($min = 10000, $max = 99999),
            'is_logged' => $this->faker->boolean(),
            'user_id' => User::inRandomOrder()->first()->id
        ];
    }
}
