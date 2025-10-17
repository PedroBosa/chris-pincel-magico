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
        Schema::create('bloqueios_horario', function (Blueprint $table) {
            $table->id();
            $table->dateTime('inicio');
            $table->dateTime('fim');
            $table->string('motivo')->nullable();
            $table->boolean('recorrente')->default(false);
            $table->unsignedTinyInteger('dia_semana')->nullable();
            $table->timestamps();

            $table->index(['inicio', 'fim']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bloqueios_horario');
    }
};
