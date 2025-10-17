<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BloqueioHorario extends Model
{
    use HasFactory;

    protected $table = 'bloqueios_horario';

    protected $fillable = [
        'inicio',
        'fim',
        'motivo',
        'recorrente',
        'dia_semana',
    ];

    protected $casts = [
        'inicio' => 'datetime',
        'fim' => 'datetime',
        'recorrente' => 'boolean',
    ];
}
