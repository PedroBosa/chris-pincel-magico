<?php

namespace Database\Seeders;

use App\Models\Agendamento;
use App\Models\Servico;
use App\Models\User;
use Illuminate\Database\Seeder;

class AgendamentoTesteSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        
        // Cria serviços de beleza se não existirem
        $servicoDesign = Servico::firstOrCreate(
            ['slug' => 'design-de-sobrancelhas'],
            [
                'nome' => 'Design de Sobrancelhas',
                'descricao' => 'Design de sobrancelhas com pinça',
                'duracao_minutos' => 30,
                'preco' => 40.00,
                'ativo' => true
            ]
        );

        $servicoHenna = Servico::firstOrCreate(
            ['slug' => 'henna'],
            [
                'nome' => 'Henna',
                'descricao' => 'Coloração de sobrancelhas com henna',
                'duracao_minutos' => 45,
                'preco' => 60.00,
                'ativo' => true
            ]
        );

        $servicoCilios = Servico::firstOrCreate(
            ['slug' => 'extensao-cilios'],
            [
                'nome' => 'Extensão de Cílios',
                'descricao' => 'Aplicação de extensão de cílios fio a fio',
                'duracao_minutos' => 90,
                'preco' => 120.00,
                'ativo' => true
            ]
        );

        $servicoLimpeza = Servico::firstOrCreate(
            ['slug' => 'limpeza-pele'],
            [
                'nome' => 'Limpeza de Pele',
                'descricao' => 'Limpeza facial profunda',
                'duracao_minutos' => 60,
                'preco' => 80.00,
                'ativo' => true
            ]
        );

        // Limpa agendamentos antigos
        Agendamento::query()->delete();

        // Agendamentos de hoje
        $hoje = now();
        
        Agendamento::create([
            'user_id' => $user->id,
            'servico_id' => $servicoDesign->id,
            'data_hora_inicio' => $hoje->copy()->setTime(10, 0, 0),
            'data_hora_fim' => $hoje->copy()->setTime(10, 30, 0),
            'status' => 'CONFIRMADO',
            'tipo' => 'NORMAL',
            'valor_total' => 40.00,
            'canal_origem' => 'ADMIN'
        ]);

        Agendamento::create([
            'user_id' => $user->id,
            'servico_id' => $servicoHenna->id,
            'data_hora_inicio' => $hoje->copy()->setTime(14, 0, 0),
            'data_hora_fim' => $hoje->copy()->setTime(14, 45, 0),
            'status' => 'PENDENTE',
            'tipo' => 'NORMAL',
            'valor_total' => 60.00,
            'canal_origem' => 'SITE'
        ]);

        Agendamento::create([
            'user_id' => $user->id,
            'servico_id' => $servicoLimpeza->id,
            'data_hora_inicio' => $hoje->copy()->setTime(16, 0, 0),
            'data_hora_fim' => $hoje->copy()->setTime(17, 0, 0),
            'status' => 'CONCLUIDO',
            'tipo' => 'NORMAL',
            'valor_total' => 80.00,
            'canal_origem' => 'ADMIN'
        ]);

        // Agendamentos de amanhã
        $amanha = now()->addDay();
        
        Agendamento::create([
            'user_id' => $user->id,
            'servico_id' => $servicoCilios->id,
            'data_hora_inicio' => $amanha->copy()->setTime(9, 0, 0),
            'data_hora_fim' => $amanha->copy()->setTime(10, 30, 0),
            'status' => 'CONFIRMADO',
            'tipo' => 'NORMAL',
            'valor_total' => 120.00,
            'canal_origem' => 'ADMIN'
        ]);

        Agendamento::create([
            'user_id' => $user->id,
            'servico_id' => $servicoDesign->id,
            'data_hora_inicio' => $amanha->copy()->setTime(11, 30, 0),
            'data_hora_fim' => $amanha->copy()->setTime(12, 0, 0),
            'status' => 'CONFIRMADO',
            'tipo' => 'NORMAL',
            'valor_total' => 40.00,
            'canal_origem' => 'SITE'
        ]);

        // Agendamento de quinta-feira
        $quinta = now()->next('Thursday');
        
        Agendamento::create([
            'user_id' => $user->id,
            'servico_id' => $servicoHenna->id,
            'data_hora_inicio' => $quinta->copy()->setTime(15, 0, 0),
            'data_hora_fim' => $quinta->copy()->setTime(15, 45, 0),
            'status' => 'PENDENTE',
            'tipo' => 'NORMAL',
            'valor_total' => 60.00,
            'canal_origem' => 'SITE'
        ]);

        // Agendamento cancelado
        Agendamento::create([
            'user_id' => $user->id,
            'servico_id' => $servicoLimpeza->id,
            'data_hora_inicio' => $quinta->copy()->setTime(13, 0, 0),
            'data_hora_fim' => $quinta->copy()->setTime(14, 0, 0),
            'status' => 'CANCELADO',
            'tipo' => 'NORMAL',
            'valor_total' => 80.00,
            'canal_origem' => 'ADMIN'
        ]);

        $this->command->info('✅ Agendamentos de teste criados com sucesso!');
        $this->command->info('📅 Total: ' . Agendamento::count() . ' agendamentos');
    }
}
