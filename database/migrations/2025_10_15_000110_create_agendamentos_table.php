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
        Schema::create('agendamentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('servico_id')->constrained('servicos')->cascadeOnDelete();
            $table->foreignId('agendamento_original_id')->nullable()->constrained('agendamentos')->nullOnDelete();
            $table->string('status', 30)->default('PENDENTE');
            $table->string('tipo', 20)->default('NORMAL');
            $table->dateTime('data_hora_inicio');
            $table->dateTime('data_hora_fim');
            $table->decimal('valor_total', 10, 2);
            $table->decimal('valor_sinal', 10, 2)->default(0);
            $table->decimal('taxa_cancelamento', 10, 2)->default(0);
            $table->boolean('pagamento_confirmado')->default(false);
            $table->boolean('lembrete_enviado')->default(false);
            $table->string('canal_origem', 30)->default('SITE');
            $table->json('metadados')->nullable();
            $table->timestamps();

            $table->index(['data_hora_inicio', 'servico_id']);
            $table->index(['status', 'data_hora_inicio']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agendamentos');
    }
};
