<?php

namespace Database\Seeders;

use App\Models\Category;
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
            Entry::factory()
                ->count(100)
                ->create();
        }
    }
}
