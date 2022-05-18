<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CommentsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'book_id' => $this->faker->numberBetween(1, 10),
            'name' => $this->faker->name(),
            'body' => $this->faker->text(),
            'ip' => $this->faker->ipv4,
        ];
    }
}
