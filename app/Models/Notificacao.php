<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notificacao extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'agendamento_id',
        'canal',
        'assunto',
        'mensagem',
        'status',
        'enviado_em',
        'payload',
    ];

    protected $casts = [
        'enviado_em' => 'datetime',
        'payload' => 'array',
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function agendamento(): BelongsTo
    {
        return $this->belongsTo(Agendamento::class);
    }
}
