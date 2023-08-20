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
            $table->uuid('uuid')->unique();
            $table->uuid('user_id')->index();
            $table->uuid('category_id')->index()->nullable();
            $table->uuid('credit_card_id')->index()->nullable();
            $table->uuid('bank_account_id')->index()->nullable();
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
                ->references('uuid')
                ->on('users');

            $table->foreign('category_id')
                ->references('uuid')
                ->on('categories');

            $table->foreign('credit_card_id')
                ->references('uuid')
                ->on('credit_cards');

            $table->foreign('bank_account_id')
                ->references('uuid')
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
