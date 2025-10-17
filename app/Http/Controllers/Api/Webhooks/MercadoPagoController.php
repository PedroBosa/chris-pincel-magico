<?php

namespace App\Http\Controllers\Api\Webhooks;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MercadoPagoController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        // TODO: delegar para PagamentoService::confirmarPagamento
        return response()->json(['received' => true], Response::HTTP_ACCEPTED);
    }
}
