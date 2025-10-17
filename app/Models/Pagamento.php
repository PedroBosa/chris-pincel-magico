<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pagamento extends Model
{
    use HasFactory;

    protected $fillable = [
        'agendamento_id',
        'gateway',
        'referencia_gateway',
        'status',
        'valor_total',
        'valor_capturado',
        'forma_pagamento',
        'payload',
        'pago_em',
    ];

    protected $casts = [
        'valor_total' => 'decimal:2',
        'valor_capturado' => 'decimal:2',
        'payload' => 'array',
        'pago_em' => 'datetime',
    ];

    public function agendamento(): BelongsTo
    {
        return $this->belongsTo(Agendamento::class);
    }
}
