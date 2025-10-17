<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FotoCliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'agendamento_id',
        'caminho',
        'legenda',
        'publicado',
        'ordem',
    ];

    protected $casts = [
        'publicado' => 'boolean',
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
