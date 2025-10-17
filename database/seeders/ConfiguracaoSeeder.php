<?php

namespace Database\Seeders;

use App\Models\Configuracao;
use Illuminate\Database\Seeder;

class ConfiguracaoSeeder extends Seeder
{
    public function run(): void
    {
        $configuracoes = [
            // Informações do Site
            'site_nome' => 'Chris Pincel Mágico',
            'site_email' => 'contato@chrispincelmagico.com',
            'site_telefone' => '(85) 98765-4321',
            'whatsapp_numero' => '+5585987654321',
            'endereco_floriano' => 'Floriano, Piauí',
            'endereco_barao' => 'Barão de Grajaú, Maranhão',
            'site_instagram' => '@chrispincelmagico',
            'site_facebook' => '',
            
            // Sistema de Pontos e Agendamentos
            'dias_expiracao_pontos' => 365,
            'horario_lembrete_horas' => 24,
            'pontos_por_real' => 1,
            'desconto_pontos_percentual' => 30,
            'taxa_cancelamento_percentual' => 50,
            'minutos_antecedencia_cancelamento' => 720,
            'limite_agendamentos_simultaneos' => 3,
            'dias_antecedencia_agendamento' => 30,
            'tempo_medio_atendimento' => 60,
        ];

        foreach ($configuracoes as $chave => $valor) {
            Configuracao::updateOrCreate(
                ['chave' => $chave],
                ['valor' => ['valor' => $valor]]
            );
        }
    }
}
