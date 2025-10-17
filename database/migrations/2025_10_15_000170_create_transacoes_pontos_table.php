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
        Schema::create('transacoes_pontos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pontos_fidelidade_id')->constrained('pontos_fidelidade')->cascadeOnDelete();
            $table->foreignId('agendamento_id')->nullable()->constrained('agendamentos')->nullOnDelete();
            $table->string('tipo', 30);
            $table->integer('pontos');
            $table->decimal('valor_referencia', 10, 2)->nullable();
            $table->string('descricao')->nullable();
            $table->json('metadados')->nullable();
            $table->timestamp('registrado_em')->useCurrent();
            $table->timestamps();

            $table->index(['tipo', 'registrado_em']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transacoes_pontos');
    }
};
