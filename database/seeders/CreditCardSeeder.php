<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\CreditCard;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CreditCardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (User::get() as $user) {
            CreditCard::factory()
                ->count(2)
                ->create([
                    'user_id' => $user->id,
                ]);
            CreditCard::withoutGlobalScopes()
                ->where('user_id', $user->id)
                ->limit(1)
                ->update([
                    'main_card' => true
            ]);
        }

    }
}
