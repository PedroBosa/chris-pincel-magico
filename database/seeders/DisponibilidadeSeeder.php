<?php

namespace Database\Seeders;

use App\Models\Disponibilidade;
use Illuminate\Database\Seeder;

class DisponibilidadeSeeder extends Seeder
{
    public function run(): void
    {
        // Limpa disponibilidades existentes
        Disponibilidade::truncate();

        // Horários padrão de funcionamento
        $horariosTrabalho = [
            // Segunda a Sexta (1-5)
            ['dia_semana' => 1, 'hora_inicio' => '09:00', 'hora_fim' => '18:00', 'ativo' => true],
            ['dia_semana' => 2, 'hora_inicio' => '09:00', 'hora_fim' => '18:00', 'ativo' => true],
            ['dia_semana' => 3, 'hora_inicio' => '09:00', 'hora_fim' => '18:00', 'ativo' => true],
            ['dia_semana' => 4, 'hora_inicio' => '09:00', 'hora_fim' => '18:00', 'ativo' => true],
            ['dia_semana' => 5, 'hora_inicio' => '09:00', 'hora_fim' => '18:00', 'ativo' => true],
            
            // Sábado (6) - Horário reduzido
            ['dia_semana' => 6, 'hora_inicio' => '09:00', 'hora_fim' => '14:00', 'ativo' => true],
            
            // Domingo (0) - Fechado
            ['dia_semana' => 0, 'hora_inicio' => '00:00', 'hora_fim' => '00:00', 'ativo' => false],
        ];

        foreach ($horariosTrabalho as $horario) {
            Disponibilidade::create($horario);
        }

        $this->command->info('✅ Disponibilidades criadas com sucesso!');
        $this->command->info('📅 Segunda a Sexta: 09:00 - 18:00');
        $this->command->info('📅 Sábado: 09:00 - 14:00');
        $this->command->info('📅 Domingo: Fechado');
    }
}
