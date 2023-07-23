<?php

namespace Database\Factories;

use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = ['expense', 'income'];

        return [
            'user_id' => '1',
            'name' => $this->faker->name,
            'description' => $this->faker->word(3),
            'color' => '#CCCAEE',
            'type' => Arr::random($types),
        ];
    }
}
