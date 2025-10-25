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
        Schema::table('agendamentos', function (Blueprint $table) {
            $table->index(['user_id', 'status'], 'agendamentos_user_status_index');
            $table->index(['data_hora_inicio', 'data_hora_fim'], 'agendamentos_periodo_index');
        });

        Schema::table('pagamentos', function (Blueprint $table) {
            $table->index(['status', 'forma_pagamento'], 'pagamentos_status_forma_index');
            $table->index('pago_em', 'pagamentos_pago_em_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agendamentos', function (Blueprint $table) {
            $table->dropIndex('agendamentos_user_status_index');
            $table->dropIndex('agendamentos_periodo_index');
        });

        Schema::table('pagamentos', function (Blueprint $table) {
            $table->dropIndex('pagamentos_status_forma_index');
            $table->dropIndex('pagamentos_pago_em_index');
        });
    }
};
