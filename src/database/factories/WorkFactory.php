<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
// use App\Models\Work;

class WorkFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            // 'user_id' => $this->faker->numberBetween(1,2),
            // 'date' => $this->faker->dateTimeBetween('-2 month','+2 month'),
            // 'start_time' => $this->faker->dateTime('H:i'),
            // 'end_time' => $this->faker->dateTime()->modify('+3hours')->format('H:i'),
            // 'status' => $this ->faker->numberBetween('1,3'),
        ];
    }
}
