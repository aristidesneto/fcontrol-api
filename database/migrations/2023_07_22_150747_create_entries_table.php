<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('entries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('credit_card_id')->nullable();
            $table->unsignedBigInteger('bank_account_id')->nullable();
            $table->enum('type', ['income', 'expense'])->default('expense');
            $table->string('title')->nullable()->comment('Título do lançamento');
            $table->decimal('amount', 10, 2);
            $table->integer('parcel')->nullable()->comment('Número da parcela');
            $table->integer('total_parcel')->nullable()->comment('Número total da parcela');
            $table->date('due_date')->nullable()->comment('Data de vencimento');
            $table->date('payday')->nullable()->comment('Data de pagamento');
            $table->boolean('is_recurring')->default(false)->comment('Despesa recorrente');
            $table->date('start_date')->nullable()->comment('Data inicial da despesa/Referência receita');
            $table->integer('sequence')->nullable()->comment('Agrupamento de parcelas recorrentes');
            $table->string('observation')->nullable();
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users');

            $table->foreign('category_id')
                ->references('id')
                ->on('categories');

            $table->foreign('credit_card_id')
                ->references('id')
                ->on('credit_cards');

            $table->foreign('bank_account_id')
                ->references('id')
                ->on('bank_accounts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entries');
    }
};
