<?php

namespace Database\Factories;

use App\Models\BankAccount;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Category;
use App\Models\CreditCard;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
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

        $date = random_int(2023, 2023) . '/' . random_int(1, 12) . '/' . random_int(1, 28);

        $category = Category::withoutGlobalScopes()->where('type', Arr::random($types))->inRandomOrder()->first();

        return [
            'uuid' => Str::uuid()->toString(),
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
            'credit_card_id' => CreditCard::factory(),
            'bank_account_id' => BankAccount::factory(),
            'type' => $category->type,
            'title' => $this->faker->name,
            'amount' => $this->faker->numberBetween(100, 2000),
            'parcel' => 1,
            'due_date' => $date,
            'payday' => Carbon::parse($date)->addDay(),
            'is_recurring' => false,
            'start_date' => $date,
        ];
    }
}
