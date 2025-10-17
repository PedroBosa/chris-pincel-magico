<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PontosFidelidade extends Model
{
    use HasFactory;

    protected $table = 'pontos_fidelidade';

    protected $fillable = [
        'user_id',
        'pontos_atuais',
        'pontos_acumulados',
        'ultima_atualizacao',
    ];

    protected $casts = [
        'ultima_atualizacao' => 'datetime',
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function transacoes(): HasMany
    {
        return $this->hasMany(TransacaoPonto::class, 'pontos_fidelidade_id');
    }
}
