<?php

namespace App\Models;

use App\Services\ProgramaFidelidadeService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Agendamento extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::updated(function (self $agendamento): void {
            if (! $agendamento->wasChanged('status')) {
                return;
            }

            $service = app(ProgramaFidelidadeService::class);
            $statusAtual = strtoupper((string) $agendamento->status);
            $statusAnterior = strtoupper((string) $agendamento->getOriginal('status'));

            if ($statusAtual === 'CONCLUIDO') {
                $service->registrarCreditoConclusao($agendamento);
            }

            if ($statusAnterior === 'CONCLUIDO' && $statusAtual !== 'CONCLUIDO') {
                $service->estornarCreditoConclusao($agendamento);
            }

            if (in_array($statusAtual, ['CANCELADO', 'NO_SHOW'], true)) {
                $service->estornarDebitoResgate($agendamento);
            }
        });

        static::deleted(function (self $agendamento): void {
            $service = app(ProgramaFidelidadeService::class);
            $service->estornarCreditoConclusao($agendamento);
            $service->estornarDebitoResgate($agendamento);
        });
    }

    protected $fillable = [
        'user_id',
        'servico_id',
        'promocao_id',
        'codigo_cupom_usado',
        'agendamento_original_id',
        'status',
        'tipo',
        'data_hora_inicio',
        'data_hora_fim',
        'valor_total',
        'valor_desconto',
        'valor_original',
        'valor_sinal',
        'forma_pagamento',
        'forma_pagamento_sinal',
        'taxa_cancelamento',
        'pagamento_confirmado',
        'lembrete_enviado',
        'canal_origem',
        'metadados',
        'observacoes',
    ];

    protected $casts = [
        'data_hora_inicio' => 'datetime',
        'data_hora_fim' => 'datetime',
        'valor_total' => 'decimal:2',
        'valor_desconto' => 'decimal:2',
        'valor_original' => 'decimal:2',
        'valor_sinal' => 'decimal:2',
        'taxa_cancelamento' => 'decimal:2',
        'pagamento_confirmado' => 'boolean',
        'lembrete_enviado' => 'boolean',
        'metadados' => 'array',
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function servico(): BelongsTo
    {
        return $this->belongsTo(Servico::class);
    }

    public function promocao(): BelongsTo
    {
        return $this->belongsTo(Promocao::class);
    }

    public function agendamentoOriginal(): BelongsTo
    {
        return $this->belongsTo(self::class, 'agendamento_original_id');
    }

    public function retoques(): HasMany
    {
        return $this->hasMany(self::class, 'agendamento_original_id');
    }

    public function pagamentos(): HasMany
    {
        return $this->hasMany(Pagamento::class);
    }

    public function transacoesPontos(): HasMany
    {
        return $this->hasMany(TransacaoPonto::class);
    }

    public function avaliacao(): HasOne
    {
        return $this->hasOne(Avaliacao::class);
    }

    public function fotos(): HasMany
    {
        return $this->hasMany(FotoCliente::class);
    }

    public function notificacoes(): HasMany
    {
        return $this->hasMany(Notificacao::class);
    }
}
