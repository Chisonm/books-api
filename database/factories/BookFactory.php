<?php

namespace Database\Factories;
use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Book::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(),
            'released' => $this->faker->dateTime(),
            'authors' => $this->faker->name(),
            'country' => $this->faker->country(),
            'publisher' => $this->faker->name(),
            'number_of_pages' => $this->faker->randomDigit(),
            'isbn' => $this->faker->randomNumber(),
        ];
    }
}
