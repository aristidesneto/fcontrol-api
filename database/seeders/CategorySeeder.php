<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = config('agenda.categories');

        foreach (User::get() as $user) {
            foreach ($categories as $item) {
                Category::create([
                    'user_id' => $user->id,
                    'name' => $item['name'],
                    'color' => $item['color'],
                    'type' => $item['type'],
                    'status' => $item['status'],
                ]);
            }
        }
    }
}
