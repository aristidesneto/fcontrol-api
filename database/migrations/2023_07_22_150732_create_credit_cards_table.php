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
            $table->unsignedBigInteger('user_id');
            $table->string('name');
            $table->string('number', 4);
            $table->integer('best_date')->comment('Melhor data para compra');
            $table->integer('due_date')->comment('Dia do vencimento');
            $table->decimal('limit', 10, 2);
            $table->boolean('status')->default(true);
            $table->boolean('main_card')->default(false);
            $table->timestamps(6);

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
        Schema::dropIfExists('credit_cards');
    }
};
