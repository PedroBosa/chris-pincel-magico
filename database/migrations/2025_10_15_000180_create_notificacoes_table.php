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
        Schema::create('notificacoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('agendamento_id')->nullable()->constrained('agendamentos')->nullOnDelete();
            $table->string('canal', 30);
            $table->string('assunto')->nullable();
            $table->text('mensagem');
            $table->string('status', 30)->default('PENDENTE');
            $table->timestamp('enviado_em')->nullable();
            $table->json('payload')->nullable();
            $table->timestamps();

            $table->index(['canal', 'status']);
            $table->index(['enviado_em']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notificacoes');
    }
};
