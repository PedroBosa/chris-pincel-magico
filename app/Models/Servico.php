<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Servico extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'slug',
        'descricao',
        'imagem_capa',
        'duracao_minutos',
        'preco',
        'preco_retoque',
        'dias_para_retoque',
        'ativo',
    ];

    protected $casts = [
        'preco' => 'decimal:2',
        'preco_retoque' => 'decimal:2',
        'ativo' => 'boolean',
    ];

    public function agendamentos(): HasMany
    {
        return $this->hasMany(Agendamento::class);
    }

    public function listaEspera(): HasMany
    {
        return $this->hasMany(ListaEspera::class);
    }
}
