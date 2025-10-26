<?php

return [
    // Pontos gerados por real pago (ex.: 1 ponto para cada R$ 1)
    'pontos_por_real' => 1,

    // Valor abatido em reais para cada ponto resgatado
    'valor_por_ponto' => 0.10,

    // Quantidade mínima de pontos para permitir resgate
    'resgate_minimo' => 50,

    // Percentual máximo do valor do serviço que pode ser coberto com pontos
    'maximo_percentual_resgate' => 50,

    // Bônus automático na primeira conclusão (0 para desativado)
    'bonus_primeira_compra' => 0,

    // Estrutura de níveis do programa, ordenada por requisito mínimo de pontos acumulados
    'niveis' => [
        [
            'slug' => 'essencial',
            'nome' => 'Essencial',
            'minimo' => 0,
            'descricao' => 'Você faz parte do nosso círculo especial. Continue acumulando pontos para desbloquear vantagens.',
        ],
        [
            'slug' => 'premium',
            'nome' => 'Premium',
            'minimo' => 500,
            'descricao' => 'Atendimentos prioritários e convites exclusivos para ações especiais.',
        ],
        [
            'slug' => 'vip',
            'nome' => 'VIP',
            'minimo' => 1200,
            'descricao' => 'Nível máximo do programa: mimos em cada visita e experiências personalizadas.',
        ],
    ],
];
