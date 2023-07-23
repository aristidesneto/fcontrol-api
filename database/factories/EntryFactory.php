<?php

namespace Database\Factories;

use Carbon\Carbon;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Entry>
 */
class EntryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = ['expense', 'income'];

        $due_date = Carbon::now()->addDays(random_int(1, 30));

        $category = Category::withoutGlobalScopes()->where('type', Arr::random($types))->inRandomOrder()->first();

        return [
            'user_id' => $category->user_id,
            'category_id' => $category->id,
            'credit_card_id' => random_int(1, 2),
            'bank_account_id' => random_int(1, 5),
            'type' => $category->type,
            'title' => $this->faker->name,
            'amount' => $this->faker->numberBetween(10, 200),
            'parcel' => 1,
            'due_date' => $due_date,
            'payday' => Carbon::parse($due_date)->addDay(),
            'is_recurring' => false,
            'start_date' => Carbon::now(),
        ];
    }
}
