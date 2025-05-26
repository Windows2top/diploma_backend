<?php

namespace Database\Factories;

use App\Models\Test;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Question>
 */
class QuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'test_id' => Test::inRandomOrder()->first()->id,
            'title' => fake()->words(3, true),
            'text' => fake()->paragraph(3),
            'type' => fake()->randomElement(['one answer', 'many answers'])
        ];
    }
}
