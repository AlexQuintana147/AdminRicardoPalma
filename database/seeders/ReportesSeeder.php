<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Cita;
use App\Models\Paciente;
use App\Models\Medico;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener IDs de pacientes y médicos
        $pacienteIds = Paciente::pluck('id')->toArray();
        $medicoIds = Medico::pluck('id')->toArray();
        
        // Si no hay datos, no continuar
        if (empty($pacienteIds) || empty($medicoIds)) {
            $this->command->error('No hay pacientes o médicos registrados. Ejecute primero los seeders correspondientes.');
            return;
        }
        
        $this->command->info('Generando datos de prueba para reportes...');
        
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
        
        // Diagnósticos de ejemplo
        $diagnosticos = [
            'Resfriado común',
            'Gastroenteritis',
            'Migraña',
            'Hipertensión arterial',
            'Infección urinaria',
            'Faringitis',
            'Ansiedad',
            'Lumbalgia',
            'Dermatitis',
            'Diabetes tipo 2',
            null // Algunas citas no tendrán diagnóstico
        ];
        
        // Observaciones de ejemplo
        $observaciones = [
            'Paciente presenta mejoría',
            'Se recomienda seguimiento en 2 semanas',
            'Requiere exámenes adicionales',
            'Paciente con antecedentes familiares',
            'Primera consulta por este motivo',
            'Paciente alérgico a penicilina',
            'Seguimiento de tratamiento anterior',
            null, // Algunas citas no tendrán observaciones
            null,
            null
        ];
        
        // Crear citas históricas (últimos 6 meses) para tener datos de reportes
        $fechaInicio = Carbon::now()->subMonths(6);
        $fechaFin = Carbon::now()->addDays(30); // Incluir algunas citas futuras
        
        $this->command->info('Generando 100 citas históricas para reportes...');
        
        // Crear 100 citas de ejemplo distribuidas en los últimos 6 meses
        for ($i = 0; $i < 100; $i++) {
            // Generar fecha aleatoria entre hace 6 meses y 30 días en el futuro
            $fecha = Carbon::createFromTimestamp(
                rand($fechaInicio->timestamp, $fechaFin->timestamp)
            );
            
            // Ajustar la hora entre 8:00 y 18:00 en intervalos de 30 minutos
            $hora = rand(8, 17);
            $minutos = rand(0, 1) * 30;
            $fecha->setTime($hora, $minutos, 0);
            
            // Determinar estado según la fecha
            $estado = $fecha->isPast() ? 
                $estados[array_rand(array_slice($estados, 2, 4))] : // Estados pasados
                $estados[array_rand(array_slice($estados, 0, 2))];  // Estados futuros
            
            // Calificación solo para citas asistidas
            $calificacion = ($estado === 'asistida') ? rand(1, 5) : null;
            
            // Diagnóstico solo para citas asistidas
            $diagnostico = ($estado === 'asistida') ? 
                $diagnosticos[array_rand($diagnosticos)] : null;
            
            // Crear la cita
            DB::table('citas')->insert([
                'paciente_id' => $pacienteIds[array_rand($pacienteIds)],
                'medico_id' => $medicoIds[array_rand($medicoIds)],
                'recepcionista_id' => null, // Opcional
                'motivo' => $motivos[array_rand($motivos)],
                'fecha_hora' => $fecha,
                'estado' => $estado,
                'urgente' => rand(0, 10) < 2, // 20% de probabilidad de ser urgente
                'observaciones' => $observaciones[array_rand($observaciones)],
                'diagnostico' => $diagnostico,
                'calificacion' => $calificacion,
                'created_at' => $fecha->copy()->subDays(rand(1, 14)),
                'updated_at' => $fecha->copy()->subHours(rand(1, 24)),
            ]);
        }
        
        $this->command->info('Datos de prueba para reportes generados correctamente.');
    }
}