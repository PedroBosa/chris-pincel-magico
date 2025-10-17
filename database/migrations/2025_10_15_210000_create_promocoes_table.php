<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('promocoes')) {
            return;
        }

        Schema::create('promocoes', function (Blueprint $table): void {
            $table->id();
            $table->string('titulo');
            $table->string('slug')->unique();
            $table->text('descricao')->nullable();
            $table->enum('tipo', ['VALOR', 'PERCENTUAL']);
            $table->decimal('valor_desconto', 10, 2)->nullable();
            $table->unsignedTinyInteger('percentual_desconto')->nullable();
            $table->string('codigo_cupom')->nullable()->unique();
            $table->dateTime('inicio_vigencia')->nullable();
            $table->dateTime('fim_vigencia')->nullable();
            $table->unsignedInteger('limite_uso')->nullable();
            $table->unsignedInteger('usos_realizados')->default(0);
            $table->boolean('ativo')->default(true);
            $table->json('restricoes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promocoes');
    }
};
