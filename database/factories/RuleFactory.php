<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RuleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $type = $this->faker->randomElement(['admin', 'owner']);
        return [
            'name' => $type
        ];
    }
}
