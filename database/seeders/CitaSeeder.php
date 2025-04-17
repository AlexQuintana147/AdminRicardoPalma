<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Paciente;
use App\Models\Medico;
use App\Models\Recepcionista;
use Carbon\Carbon;

class CitaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener IDs de pacientes, médicos y recepcionistas
        $pacienteIds = Paciente::pluck('id')->toArray();
        $medicoIds = Medico::pluck('id')->toArray();
        $recepcionistaIds = Recepcionista::pluck('id')->toArray();
        
        // Si no hay datos, no continuar
        if (empty($pacienteIds) || empty($medicoIds)) {
            return;
        }
        
        // Estados posibles para las citas
        $estados = ['pendiente', 'confirmada', 'cancelada', 'reprogramada', 'asistida', 'no_asistida'];
        
        // Motivos de ejemplo para las citas
        $motivos = [
            'Consulta general',
            'Control rutinario',
            'Dolor abdominal',
            'Fiebre alta',
            'Revisión de exámenes',
            'Dolor de cabeza persistente',
            'Problemas respiratorios',
            'Seguimiento de tratamiento',
            'Vacunación',
            'Malestar general'
        ];
        
        // Crear 30 citas de ejemplo
        for ($i = 0; $i < 30; $i++) {
            // Generar fecha aleatoria en los próximos 30 días (algunas en el pasado para ejemplos)
            $diasAleatorios = rand(-5, 30);
            $horaAleatoria = rand(8, 17);
            $minutoAleatorio = rand(0, 3) * 15; // 0, 15, 30, 45
            
            $fechaHora = Carbon::now()->addDays($diasAleatorios)
                ->setHour($horaAleatoria)
                ->setMinute($minutoAleatorio)
                ->setSecond(0);
            
            // Determinar estado basado en la fecha
            $estadoIndex = 0; // pendiente por defecto
            if ($fechaHora->isPast()) {
                // Si es en el pasado, puede ser asistida, no_asistida o cancelada
                $estadoIndex = rand(2, 5);
            } else if ($fechaHora->diffInDays(Carbon::now()) < 2) {
                // Si es en los próximos 2 días, puede estar confirmada
                $estadoIndex = rand(0, 1);
            }
            
            DB::table('citas')->insert([
                'paciente_id' => $pacienteIds[array_rand($pacienteIds)],
                'medico_id' => $medicoIds[array_rand($medicoIds)],
                'recepcionista_id' => !empty($recepcionistaIds) ? $recepcionistaIds[array_rand($recepcionistaIds)] : null,
                'motivo' => $motivos[array_rand($motivos)],
                'fecha_hora' => $fechaHora,
                'estado' => $estados[$estadoIndex],
                'urgente' => rand(0, 10) > 8, // 20% de probabilidad de ser urgente
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}