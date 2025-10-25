<?php

use Illuminate\Support\Facades\Cache;

if (! function_exists('config_site')) {
    /**
     * Obtém uma configuração do site do banco de dados
     *
     * @param string $chave Chave da configuração
     * @param mixed $default Valor padrão caso não encontre
     * @return mixed
     */
    function config_site(string $chave, $default = null)
    {
        if (! isset($GLOBALS['config_site_runtime']) || ! is_array($GLOBALS['config_site_runtime'])) {
            $GLOBALS['config_site_runtime'] = Cache::rememberForever('config_site:all', function () {
                return \App\Models\Configuracao::query()
                    ->select(['chave', 'valor'])
                    ->get()
                    ->mapWithKeys(function ($configuracao) {
                        $valor = $configuracao->valor;

                        if (is_array($valor) && array_key_exists('valor', $valor)) {
                            $valor = $valor['valor'];
                        }

                        return [$configuracao->chave => $valor];
                    })
                    ->toArray();
            });
        }

        return $GLOBALS['config_site_runtime'][$chave] ?? $default;
    }
}

if (! function_exists('config_site_clear_cache')) {
    /**
     * Limpa o cache das configurações do site.
     */
    function config_site_clear_cache(): void
    {
        Cache::forget('config_site:all');
        unset($GLOBALS['config_site_runtime']);
    }
}

if (! function_exists('whatsapp_link')) {
    /**
     * Gera link formatado do WhatsApp
     *
     * @param string|null $mensagem Mensagem pré-preenchida (opcional)
     * @return string
     */
    function whatsapp_link(?string $mensagem = null): string
    {
        $numero = config_site('whatsapp_numero', '+5585987654321');
        
        // Remove caracteres não numéricos exceto o +
        $numero = preg_replace('/[^0-9+]/', '', $numero);
        
        $url = "https://wa.me/{$numero}";
        
        if ($mensagem) {
            $url .= '?text=' . urlencode($mensagem);
        }
        
        return $url;
    }
}

if (! function_exists('site_info')) {
    /**
     * Obtém informações do site
     *
     * @param string $campo
     * @return mixed
     */
    function site_info(string $campo)
    {
        $info = [
            'nome' => config_site('site_nome', 'Chris Pincel Mágico'),
            'email' => config_site('site_email', 'contato@chrispincelmagico.com'),
            'telefone' => config_site('site_telefone', '(85) 98765-4321'),
            'whatsapp' => config_site('whatsapp_numero', '+5585987654321'),
            'endereco_floriano' => config_site('endereco_floriano', 'Floriano, Piauí'),
            'endereco_barao' => config_site('endereco_barao', 'Barão de Grajaú, Maranhão'),
            'instagram' => config_site('site_instagram', '@chrispincelmagico'),
            'facebook' => config_site('site_facebook', ''),
        ];

        return $info[$campo] ?? null;
    }
}

if (! function_exists('enderecos_estudios')) {
    /**
     * Retorna array com todos os endereços dos estúdios
     *
     * @return array
     */
    function enderecos_estudios(): array
    {
        return [
            'floriano' => [
                'nome' => 'Estúdio Floriano',
                'cidade' => 'Floriano',
                'estado' => 'PI',
                'endereco' => config_site('endereco_floriano', 'Floriano, Piauí'),
                'emoji' => '📍',
            ],
            'barao' => [
                'nome' => 'Estúdio Barão de Grajaú',
                'cidade' => 'Barão de Grajaú',
                'estado' => 'MA',
                'endereco' => config_site('endereco_barao', 'Barão de Grajaú, Maranhão'),
                'emoji' => '📍',
            ],
        ];
    }
}
