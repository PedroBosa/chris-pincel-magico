<?php

namespace Database\Seeders;

use App\Models\Agendamento;
use App\Models\Avaliacao;
use App\Models\User;
use Illuminate\Database\Seeder;

class AvaliacaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $comentarios = [
            5 => [
                'Experiência maravilhosa! Profissional super atenciosa e resultado impecável. Já voltei várias vezes!',
                'Adorei o atendimento! Super carinhosa e cuidadosa. O resultado ficou perfeito, exatamente como eu queria.',
                'Melhor sobrancelha que já fiz! Ela entende exatamente o que você quer. Super recomendo!',
                'Atendimento de primeira! Ambiente limpo, profissional qualificada e resultado incrível. Voltarei sempre!',
                'Simplesmente perfeito! Desde o agendamento até o resultado final. Estou apaixonada!',
                'Profissional excelente! Sempre saio de lá satisfeita. O trabalho dela é impecável!',
            ],
            4 => [
                'Muito bom! Gostei bastante do resultado. Profissional competente e educada.',
                'Ótimo atendimento! O resultado ficou muito bonito. Só achei um pouquinho demorado.',
                'Adorei! Ficou lindo. A única sugestão seria ter mais horários disponíveis.',
                'Excelente trabalho! Ambiente agradável e resultado muito satisfatório.',
            ],
            3 => [
                'Bom atendimento, mas o resultado poderia ter sido melhor.',
                'Satisfatório. Profissional simpática, mas esperava um resultado mais natural.',
            ],
        ];

        // Buscar ou criar usuários e serviços necessários
        $usuarios = User::where('is_admin', false)->get();
        if ($usuarios->isEmpty()) {
            $this->command->info('Nenhum cliente encontrado. Criando clientes de exemplo...');
            $usuarios = collect([
                User::create([
                    'name' => 'Maria Silva',
                    'email' => 'maria.silva@example.com',
                    'password' => bcrypt('password'),
                    'is_admin' => false,
                ]),
                User::create([
                    'name' => 'Ana Santos',
                    'email' => 'ana.santos@example.com',
                    'password' => bcrypt('password'),
                    'is_admin' => false,
                ]),
                User::create([
                    'name' => 'Juliana Costa',
                    'email' => 'juliana.costa@example.com',
                    'password' => bcrypt('password'),
                    'is_admin' => false,
                ]),
            ]);
        }

        $servicos = \App\Models\Servico::all();
        if ($servicos->isEmpty()) {
            $this->command->error('Nenhum serviço encontrado. Execute o seeder de serviços primeiro.');
            return;
        }

        // Criar agendamentos concluídos se não existirem
        $agendamentos = Agendamento::where('status', 'concluido')->get();
        
        if ($agendamentos->count() < 10) {
            $this->command->info('Criando agendamentos concluídos...');
            
            for ($i = 0; $i < 10; $i++) {
                $usuario = $usuarios->random();
                $servico = $servicos->random();
                $data = now()->subDays(rand(1, 60))->setHour(rand(9, 17))->setMinute([0, 30][rand(0, 1)]);
                
                $agendamento = Agendamento::create([
                    'user_id' => $usuario->id,
                    'servico_id' => $servico->id,
                    'data_hora_inicio' => $data,
                    'data_hora_fim' => $data->copy()->addMinutes($servico->duracao_minutos ?? 60),
                    'status' => 'concluido',
                    'tipo' => 'normal',
                    'valor_total' => $servico->preco,
                    'valor_original' => $servico->preco,
                    'valor_sinal' => $servico->preco * 0.3,
                    'forma_pagamento' => ['pix', 'credit_card', 'debit_card'][rand(0, 2)],
                    'forma_pagamento_sinal' => 'pix',
                    'pagamento_confirmado' => true,
                ]);
                
                $agendamentos->push($agendamento);
            }
        }

        // Criar avaliações
        $count = 0;
        foreach ($agendamentos->take(15) as $agendamento) {
            // Verificar se já tem avaliação
            if ($agendamento->avaliacao()->exists()) {
                continue;
            }

            // Definir nota (70% são 5 estrelas, 20% são 4, 10% são 3)
            $random = rand(1, 100);
            if ($random <= 70) {
                $nota = 5;
            } elseif ($random <= 90) {
                $nota = 4;
            } else {
                $nota = 3;
            }

            // Pegar comentário aleatório baseado na nota
            $comentariosList = $comentarios[$nota] ?? ['Ótimo serviço!'];
            $comentario = $comentariosList[array_rand($comentariosList)];

            // 80% das avaliações já publicadas
            $publicado = rand(1, 100) <= 80;

            Avaliacao::create([
                'agendamento_id' => $agendamento->id,
                'user_id' => $agendamento->user_id,
                'nota' => $nota,
                'comentario' => $comentario,
                'publicado' => $publicado,
                'publicado_em' => $publicado ? now()->subDays(rand(1, 30)) : null,
            ]);

            $count++;
        }

        $this->command->info("$count avaliações criadas com sucesso!");
    }
}
