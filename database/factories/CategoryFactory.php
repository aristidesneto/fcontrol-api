<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
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
            'uuid' => Str::uuid()->toString(),
            'user_id' => User::factory(),
            'name' => $this->faker->name,
            'color' => '#CCCAEE',
            'type' => Arr::random($types),
            'status' => true,
        ];
    }
}
