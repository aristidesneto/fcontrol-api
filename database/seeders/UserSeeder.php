<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::factory()->create([
            'name' => 'Aristides Neto',
            'email' => 'aristides@admin.com',
            'password' => Hash::make('password'),
        ]);
        
        \App\Models\User::factory(2)->create();
    }
}
