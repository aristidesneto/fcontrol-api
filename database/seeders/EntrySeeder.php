<?php

namespace Database\Seeders;

use App\Models\BankAccount;
use App\Models\Category;
use App\Models\CreditCard;
use App\Models\User;
use App\Models\Entry;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Arr;

class EntrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (User::get() as $user) {
            $categories = Category::withoutGlobalScopes()->where('user_id', $user->id)->get('id');
            $credit_cards = CreditCard::withoutGlobalScopes()->where('user_id', $user->id)->get('id');
            $bank_accounts = BankAccount::withoutGlobalScopes()->where('user_id', $user->id)->get('id');

            $categories_id = collect([]);
            foreach ($categories as $item) {
                $categories_id->push($item->id);
            }
            
            $credit_cards_id = collect([]);
            foreach ($credit_cards as $item) {
                $credit_cards_id->push($item->id);
            }

            $bank_accounts_id = collect([]);
            foreach ($bank_accounts as $item) {
                $bank_accounts_id->push($item->id);
            }

            Entry::factory()
                ->count(20)
                ->create([
                    'user_id' => $user->id,
                    'category_id' => $categories_id->random(),
                    'credit_card_id' => $credit_cards_id->random(),
                    'bank_account_id' => $bank_accounts_id->random(),
                ]);
        }
    }
}
