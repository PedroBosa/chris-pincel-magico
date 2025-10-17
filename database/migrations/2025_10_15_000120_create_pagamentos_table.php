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
        Schema::create('pagamentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agendamento_id')->constrained('agendamentos')->cascadeOnDelete();
            $table->string('gateway', 50)->default('mercado_pago');
            $table->string('referencia_gateway')->unique();
            $table->string('status', 30);
            $table->decimal('valor_total', 10, 2);
            $table->decimal('valor_capturado', 10, 2)->nullable();
            $table->string('forma_pagamento', 30)->nullable();
            $table->json('payload')->nullable();
            $table->timestamp('pago_em')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagamentos');
    }
};
