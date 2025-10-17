<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disponibilidade extends Model
{
    use HasFactory;

    protected $fillable = [
        'dia_semana',
        'hora_inicio',
        'hora_fim',
        'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    public function getHoraInicioAttribute($value)
    {
        // Se o valor contém data, extrai apenas a hora
        if (is_string($value) && strlen($value) > 8) {
            return substr($value, 11, 5); // Retorna HH:MM
        }
        return $value;
    }

    public function getHoraFimAttribute($value)
    {
        // Se o valor contém data, extrai apenas a hora
        if (is_string($value) && strlen($value) > 8) {
            return substr($value, 11, 5); // Retorna HH:MM
        }
        return $value;
    }
}
