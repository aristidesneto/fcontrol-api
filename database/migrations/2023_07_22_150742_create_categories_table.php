<?php

use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('name');
            $table->string('color');
            $table->enum('type', ['income', 'expense'])->default('income');
            $table->boolean('status')->default(1);
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }

    protected function categories()
    {
        $categories = [
            ['name' => 'Alimentação', 'color' => '#183a54', 'type' => 'expense', 'status' => true],
            ['name' => 'Assinaturas e serviços', 'color' => '#f00370', 'type' => 'expense', 'status' => false],
            ['name' => 'Bares e restaurantes', 'color' => '#ae7758', 'type' => 'expense', 'status' => true],
            ['name' => 'Cuidados pessoais', 'color' => '#76b110', 'type' => 'expense', 'status' => true],
            ['name' => 'Educação', 'color' => '#3bd616', 'type' => 'expense', 'status' => true],
            ['name' => 'Família e filhos', 'color' => '#89e60f', 'type' => 'expense', 'status' => true],
            ['name' => 'Impostos e taxas', 'color' => '#e25a28', 'type' => 'expense', 'status' => true],
            ['name' => 'Lazer e hobbies', 'color' => '#e38a2d', 'type' => 'expense', 'status' => true],
            ['name' => 'Mercado', 'color' => '#688550', 'type' => 'expense', 'status' => true],
            ['name' => 'Habitação', 'color' => '#2ae250', 'type' => 'expense', 'status' => false],
            ['name' => 'Outros', 'color' => '#bf2eb2', 'type' => 'expense', 'status' => true],
            ['name' => 'Pets', 'color' => '#0dd4d2', 'type' => 'expense', 'status' => true],
            ['name' => 'Roupas', 'color' => '#8f3d05', 'type' => 'expense', 'status' => true],
            ['name' => 'Saúde', 'color' => '#89bc42', 'type' => 'expense', 'status' => false],
            ['name' => 'Trabalho', 'color' => '#4d33a4', 'type' => 'expense', 'status' => true],
            ['name' => 'Transporte', 'color' => '#e2dc4a', 'type' => 'expense', 'status' => true],
            ['name' => 'Viagem', 'color' => '#c21396', 'type' => 'expense', 'status' => true],
            
            ['name' => 'Salário', 'color' => '#674e09', 'type' => 'income', 'status' => true],
            ['name' => 'Freelancer', 'color' => '#da3878', 'type' => 'income', 'status' => true],
            ['name' => 'Vale refeição', 'color' => '#369dae', 'type' => 'income', 'status' => true],
            ['name' => 'Outros', 'color' => '#54d82a', 'type' => 'income', 'status' => true],
        ];

        $user = User::where('email', 'aristidesbneto@gmail.com')->first();

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
};
