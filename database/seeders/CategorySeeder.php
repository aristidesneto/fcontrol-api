<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Alimentação', 'color' => '#183a54', 'type' => 'expense', 'status' => 'active'],
            ['name' => 'Assinaturas e serviços', 'color' => '#f00370', 'type' => 'expense', 'status' => 'inactive'],
            ['name' => 'Bares e restaurantes', 'color' => '#ae7758', 'type' => 'expense', 'status' => 'active'],
            ['name' => 'Compras', 'color' => '#c13e67', 'type' => 'expense', 'status' => 'active'],
            ['name' => 'Cuidados pessoais', 'color' => '#76b110', 'type' => 'expense', 'status' => 'active'],
            ['name' => 'Dívidas e empréstimos', 'color' => '#6cd3b1', 'type' => 'expense', 'status' => 'inactive'],
            ['name' => 'Educação', 'color' => '#3bd616', 'type' => 'expense', 'status' => 'active'],
            ['name' => 'Família e filhos', 'color' => '#89e60f', 'type' => 'expense', 'status' => 'active'],
            ['name' => 'Impostos e taxas', 'color' => '#e25a28', 'type' => 'expense', 'status' => 'active'],
            ['name' => 'Investimentos', 'color' => '#a693c1', 'type' => 'expense', 'status' => 'inactive'],
            ['name' => 'Lazer e hobbies', 'color' => '#e38a2d', 'type' => 'expense', 'status' => 'active'],
            ['name' => 'Mercado', 'color' => '#688550', 'type' => 'expense', 'status' => 'active'],
            ['name' => 'Casa', 'color' => '#2ae250', 'type' => 'expense', 'status' => 'inactive'],
            ['name' => 'Outros', 'color' => '#bf2eb2', 'type' => 'expense', 'status' => 'active'],
            ['name' => 'Pets', 'color' => '#0dd4d2', 'type' => 'expense', 'status' => 'active'],
            ['name' => 'Presentes e doações', 'color' => '#3dfc16', 'type' => 'expense', 'status' => 'active'],
            ['name' => 'Roupas', 'color' => '#8f3d05', 'type' => 'expense', 'status' => 'active'],
            ['name' => 'Saúde', 'color' => '#89bc42', 'type' => 'expense', 'status' => 'inactive'],
            ['name' => 'Trabalho', 'color' => '#4d33a4', 'type' => 'expense', 'status' => 'active'],
            ['name' => 'Transporte', 'color' => '#e2dc4a', 'type' => 'expense', 'status' => 'active'],
            ['name' => 'Viagem', 'color' => '#c21396', 'type' => 'expense', 'status' => 'active'],
            
            ['name' => 'Salário', 'color' => '#674e09', 'type' => 'income', 'status' => 'active'],
            ['name' => 'Investimento', 'color' => '#da3878', 'type' => 'income', 'status' => 'active'],
            ['name' => 'Empréstimo', 'color' => '#369dae', 'type' => 'income', 'status' => 'active'],
            ['name' => 'Outros', 'color' => '#54d82a', 'type' => 'income', 'status' => 'active'],
        ];

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
