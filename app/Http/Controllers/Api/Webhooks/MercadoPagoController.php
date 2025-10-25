<?php

namespace App\Http\Controllers\Api\Webhooks;

use App\Http\Controllers\Controller;
use App\Services\PagamentoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\Response;

class MercadoPagoController extends Controller
{
    public function __invoke(Request $request, PagamentoService $pagamentoService): JsonResponse
    {
        $payload = $request->all();

        $referencia = Arr::get($payload, 'data.id')
            ?? Arr::get($payload, 'external_reference')
            ?? Arr::get($payload, 'referencia_gateway')
            ?? Arr::get($payload, 'referencia')
            ?? Arr::get($payload, 'id');

        if (! $referencia) {
            return response()->json([
                'received' => false,
                'message' => 'Referência do pagamento não informada.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $pagamento = $pagamentoService->confirmarPagamento($referencia, $payload);

        if (! $pagamento) {
            return response()->json([
                'received' => true,
                'message' => 'Pagamento não localizado para a referência informada.',
            ], Response::HTTP_ACCEPTED);
        }

        return response()->json([
            'received' => true,
            'status' => $pagamento->status,
        ], Response::HTTP_ACCEPTED);
    }
}
