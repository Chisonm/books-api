<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->title(),
            'released' => $this->faker->dateTime(),
            'authors' => $this->faker->name(),
            'country' => $this->faker->country(),
            'publisher' => $this->faker->name(),
            'number_of_pages' => $this->faker->randomDigit(),
            'isbn' => $this->faker->randomNumber(),
        ];
    }
}
