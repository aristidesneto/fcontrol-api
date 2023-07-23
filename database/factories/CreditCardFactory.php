<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CreditCard>
 */
class CreditCardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $due_data = $this->faker->numberBetween(1, 28);

        return [
            'user_id' => '1',
            'name' => $this->faker->name,
            'number' => $this->faker->numberBetween(1000, 9999),
            'best_date' => $due_data - 7,
            'due_date' => $due_data,
            'limit' => $this->faker->numberBetween(100, 10000),
        ];
    }
}
