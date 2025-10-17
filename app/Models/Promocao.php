<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promocao extends Model
{
    use HasFactory;

    protected $table = 'promocoes';

    protected $fillable = [
        'titulo',
        'slug',
        'descricao',
        'tipo',
        'valor_desconto',
        'percentual_desconto',
        'codigo_cupom',
        'inicio_vigencia',
        'fim_vigencia',
        'limite_uso',
        'usos_realizados',
        'ativo',
        'restricoes',
    ];

    protected $casts = [
        'valor_desconto' => 'decimal:2',
        'inicio_vigencia' => 'datetime',
        'fim_vigencia' => 'datetime',
        'ativo' => 'boolean',
        'restricoes' => 'array',
    ];

    public function agendamentos()
    {
        return $this->hasMany(Agendamento::class);
    }
}
