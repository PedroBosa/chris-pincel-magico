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
            $table->foreignId('promocao_id')->nullable()->after('servico_id')->constrained('promocoes')->nullOnDelete();
            $table->string('codigo_cupom_usado')->nullable()->after('promocao_id');
            $table->decimal('valor_desconto', 10, 2)->default(0)->after('valor_total');
            $table->decimal('valor_original', 10, 2)->nullable()->after('valor_desconto');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agendamentos', function (Blueprint $table) {
            $table->dropForeign(['promocao_id']);
            $table->dropColumn(['promocao_id', 'codigo_cupom_usado', 'valor_desconto', 'valor_original']);
        });
    }
};
