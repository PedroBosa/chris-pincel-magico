<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ListaEspera extends Model
{
    use HasFactory;

    protected $table = 'lista_espera';

    protected $fillable = [
        'servico_id',
        'user_id',
        'nome',
        'email',
        'telefone',
        'data_hora_desejada',
        'status',
        'observacoes',
    ];

    protected $casts = [
        'data_hora_desejada' => 'datetime',
    ];

    public function servico(): BelongsTo
    {
        return $this->belongsTo(Servico::class);
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
