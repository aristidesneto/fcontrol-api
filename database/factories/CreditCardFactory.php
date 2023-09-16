<?php

namespace Database\Factories;

use App\Models\User;
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

        $best_date = abs($due_data - 7);

        return [
            'user_id' => User::factory(),
            'name' => $this->faker->name,
            'number' => $this->faker->numberBetween(1000, 9999),
            'best_date' => $best_date !== 0 ? $best_date : 1,
            'due_date' => $due_data,
            'main_card' => false,
            'limit' => $this->faker->numberBetween(100, 10000),
        ];
    }
}
