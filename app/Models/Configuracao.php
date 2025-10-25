<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configuracao extends Model
{
    use HasFactory;

    protected $table = 'configuracoes';

    protected $fillable = [
        'chave',
        'valor',
        'descricao',
    ];

    protected $casts = [
        'valor' => 'array',
    ];

    public static function get(string $chave, mixed $default = null): mixed
    {
        $configuracao = static::query()
            ->where('chave', $chave)
            ->first();

        if (! $configuracao) {
            return $default;
        }

        $valor = $configuracao->valor;

        if (is_array($valor) && array_key_exists('valor', $valor)) {
            return $valor['valor'];
        }

        return $valor ?? $default;
    }
}
