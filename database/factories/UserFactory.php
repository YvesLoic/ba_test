<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => 'Yves Loic',
            'email' => 'loic@example.org',
            'phone' => '698586208',
            'email_verified_at' => now(),
            'password' => '$2y$10$OSIZCCQC3cE8N2T9Rkl5c.ZFIRCFa/Rr3c7atX4YNtxH6JjS6rKHe', // Yves1234*
            'remember_token' => Str::random(10),
            'rule' => 'admin',
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
