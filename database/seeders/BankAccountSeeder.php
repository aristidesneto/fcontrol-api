<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\BankAccount;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BankAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (User::get() as $user) {
            BankAccount::factory()
                ->count(2)
                ->create([
                    'user_id' => $user->id
                ]);
        }
    }
}
