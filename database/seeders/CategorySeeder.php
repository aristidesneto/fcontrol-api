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
            ['name' => 'Alimentação', 'color' => '#CCCAEE', 'type' => 'expense'],
            ['name' => 'Assinaturas e serviços', 'color' => '#CCCAEE', 'type' => 'expense'],
            ['name' => 'Bares e restaurantes', 'color' => '#CCCAEE', 'type' => 'expense'],
            ['name' => 'Compras', 'color' => '#CCCAEE', 'type' => 'expense'],
            ['name' => 'Cuidados pessoais', 'color' => '#CCCAEE', 'type' => 'expense'],
            ['name' => 'Dívidas e empréstimos', 'color' => '#CCCAEE', 'type' => 'expense'],
            ['name' => 'Educação', 'color' => '#CCCAEE', 'type' => 'expense'],
            ['name' => 'Família e filhos', 'color' => '#CCCAEE', 'type' => 'expense'],
            ['name' => 'Impostos e taxas', 'color' => '#CCCAEE', 'type' => 'expense'],
            ['name' => 'Investimentos', 'color' => '#CCCAEE', 'type' => 'expense'],
            ['name' => 'Lazer e hobbies', 'color' => '#CCCAEE', 'type' => 'expense'],
            ['name' => 'Mercado', 'color' => '#CCCAEE', 'type' => 'expense'],
            ['name' => 'Casa', 'color' => '#CCCAEE', 'type' => 'expense'],
            ['name' => 'Outros', 'color' => '#CCCAEE', 'type' => 'expense'],
            ['name' => 'Pets', 'color' => '#CCCAEE', 'type' => 'expense'],
            ['name' => 'Presentes e doações', 'color' => '#CCCAEE', 'type' => 'expense'],
            ['name' => 'Roupas', 'color' => '#CCCAEE', 'type' => 'expense'],
            ['name' => 'Saúde', 'color' => '#CCCAEE', 'type' => 'expense'],
            ['name' => 'Trabalho', 'color' => '#CCCAEE', 'type' => 'expense'],
            ['name' => 'Transporte', 'color' => '#CCCAEE', 'type' => 'expense'],
            ['name' => 'Viagem', 'color' => '#CCCAEE', 'type' => 'expense'],
            
            ['name' => 'Salário', 'color' => '#AAAEEE', 'type' => 'income'],
            ['name' => 'Investimento', 'color' => '#AAAEEE', 'type' => 'income'],
            ['name' => 'Empréstimo', 'color' => '#AAAEEE', 'type' => 'income'],
            ['name' => 'Outros', 'color' => '#AAAEEE', 'type' => 'income'],
        ];

        foreach (User::get() as $user) {
            foreach ($categories as $item) {
                Category::create([
                    'user_id' => $user->id,
                    'name' => $item['name'],
                    'color' => $item['color'],
                    'type' => $item['type']
                ]);
            }
        }
    }
}
