<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Promocao;
use App\Models\Servico;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CupomController extends Controller
{
    public function validar(Request $request): JsonResponse
    {
        $request->validate([
            'codigo' => ['required', 'string'],
            'servico_id' => ['required', 'exists:servicos,id'],
        ]);

        $codigo = strtoupper(trim($request->codigo));
        $servicoId = $request->servico_id;

        // Busca a promoção pelo código do cupom
        $promocao = Promocao::where('codigo_cupom', $codigo)
            ->where('ativo', true)
            ->first();

        if (!$promocao) {
            return response()->json([
                'valido' => false,
                'mensagem' => 'Cupom inválido ou não encontrado.',
            ], 404);
        }

        // Valida vigência
        $agora = now();
        if ($promocao->inicio_vigencia && $agora->lt($promocao->inicio_vigencia)) {
            return response()->json([
                'valido' => false,
                'mensagem' => 'Este cupom ainda não está disponível.',
            ], 400);
        }

        if ($promocao->fim_vigencia && $agora->gt($promocao->fim_vigencia)) {
            return response()->json([
                'valido' => false,
                'mensagem' => 'Este cupom já expirou.',
            ], 400);
        }

        // Valida limite de uso
        if ($promocao->limite_uso && $promocao->usos_realizados >= $promocao->limite_uso) {
            return response()->json([
                'valido' => false,
                'mensagem' => 'Este cupom atingiu o limite de usos.',
            ], 400);
        }

        // Busca o serviço para calcular o desconto
        $servico = Servico::findOrFail($servicoId);
        $valorOriginal = (float) $servico->preco;

        // Calcula o desconto
        if ($promocao->tipo === 'PERCENTUAL') {
            $valorDesconto = $valorOriginal * ($promocao->percentual_desconto / 100);
        } else {
            $valorDesconto = min((float) $promocao->valor_desconto, $valorOriginal);
        }

        $valorFinal = max(0, $valorOriginal - $valorDesconto);

        return response()->json([
            'valido' => true,
            'promocao_id' => $promocao->id,
            'codigo' => $promocao->codigo_cupom,
            'titulo' => $promocao->titulo,
            'tipo' => $promocao->tipo,
            'valor_original' => $valorOriginal,
            'valor_desconto' => round($valorDesconto, 2),
            'valor_final' => round($valorFinal, 2),
            'percentual' => $promocao->tipo === 'PERCENTUAL' ? $promocao->percentual_desconto : null,
            'mensagem' => $promocao->tipo === 'PERCENTUAL' 
                ? "{$promocao->percentual_desconto}% de desconto aplicado!"
                : "R$ " . number_format($valorDesconto, 2, ',', '.') . " de desconto aplicado!",
        ]);
    }
}
