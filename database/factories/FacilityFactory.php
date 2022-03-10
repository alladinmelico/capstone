<?php

namespace Database\Factories;

use App\Models\Facility;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class FacilityFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Facility::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->sentence(),
            'code' => $this->faker->word() . mt_rand(0, 99),
            'capacity' => $this->faker->numberBetween($min = 10, $max = 100),
            'building_id' => Arr::random(array_keys(config('constants.buildings'))),
            'type' => Arr::random(array_keys(config('constants.facilities.types'))),
            'department_id' => Arr::random(array_keys(config('constants.departments'))),
        ];
    }
}
