<?php

use App\Models\User;
use App\Models\CreditCard;
use Illuminate\Support\Str;
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
        Schema::create('credit_cards', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->uuid('user_id')->index();
            $table->string('name');
            $table->string('number', 4);
            $table->integer('best_date')->comment('Melhor data para compra');
            $table->integer('due_date')->comment('Dia do vencimento');
            $table->decimal('limit', 10, 2);
            $table->boolean('status')->default(true);
            $table->boolean('main_card')->default(false);
            $table->timestamps();

            $table->foreign('user_id')
                ->references('uuid')
                ->on('users');
        });

        // $user = User::where('email', 'aristidesbneto@gmail.com')->first();

        // CreditCard::create([
        //     'uuid' => Str::uuid()->toString(),
        //     'name' => 'Nubank',
        //     'user_id' => $user->id,
        //     'number' => '1234',
        //     'best_date' => '3',
        //     'due_date' => '10',
        //     'limit' => '1000',
        //     'main_card' => '1'       
        // ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credit_cards');
    }
};
