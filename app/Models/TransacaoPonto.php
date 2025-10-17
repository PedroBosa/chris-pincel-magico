<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransacaoPonto extends Model
{
    use HasFactory;

    protected $table = 'transacoes_pontos';

    protected $fillable = [
        'pontos_fidelidade_id',
        'agendamento_id',
        'tipo',
        'pontos',
        'valor_referencia',
        'descricao',
        'metadados',
        'registrado_em',
    ];

    protected $casts = [
        'valor_referencia' => 'decimal:2',
        'metadados' => 'array',
        'registrado_em' => 'datetime',
    ];

    public function carteira(): BelongsTo
    {
        return $this->belongsTo(PontosFidelidade::class, 'pontos_fidelidade_id');
    }

    public function agendamento(): BelongsTo
    {
        return $this->belongsTo(Agendamento::class);
    }
}
