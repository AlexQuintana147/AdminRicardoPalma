<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Medico;
use App\Models\Paciente;
use App\Exports\CitasExport;
use App\Exports\MedicoCitasExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use App\Models\Cita;
use Illuminate\Support\Facades\Storage;

class GenerarReportesCitas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reportes:citas
                            {tipo=general : Tipo de reporte (general, medico, paciente)}
                            {id? : ID del médico o paciente si el tipo es específico}
                            {--desde= : Fecha desde (formato Y-m-d)}
                            {--hasta= : Fecha hasta (formato Y-m-d)}
                            {--formato=excel : Formato de salida (excel, pdf)}'; 

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Genera reportes de citas médicas en formato Excel o PDF';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tipo = $this->argument('tipo');
        $id = $this->argument('id');
        $desde = $this->option('desde') ? Carbon::parse($this->option('desde')) : null;
        $hasta = $this->option('hasta') ? Carbon::parse($this->option('hasta')) : null;
        $formato = $this->option('formato');
        
        // Validar formato
        if (!in_array($formato, ['excel', 'pdf'])) {
            $this->error('Formato no válido. Use excel o pdf.');
            return 1;
        }
        
        // Validar tipo y ID si es necesario
        if (in_array($tipo, ['medico', 'paciente']) && !$id) {
            $this->error('Debe proporcionar un ID para reportes por médico o paciente.');
            return 1;
        }
        
        $this->info('Generando reporte de citas ' . $tipo . '...');
        
        // Generar nombre de archivo
        $fechaActual = Carbon::now()->format('Y-m-d_H-i-s');
        $nombreArchivo = 'reporte_citas_' . $tipo . '_' . $fechaActual;
        
        // Obtener datos según el tipo de reporte
        switch ($tipo) {
            case 'medico':
                $medico = Medico::find($id);
                if (!$medico) {
                    $this->error('Médico no encontrado.');
                    return 1;
                }
                
                if ($formato === 'excel') {
                    $path = storage_path('app/public/reportes/' . $nombreArchivo . '.xlsx');
                    Excel::store(new MedicoCitasExport($id, $desde, $hasta), 'public/reportes/' . $nombreArchivo . '.xlsx');
                } else {
                    $citas = Cita::with(['paciente', 'medico'])
                        ->where('medico_id', $id)
                        ->when($desde, function ($query) use ($desde) {
                            return $query->whereDate('fecha_hora', '>=', $desde);
                        })
                        ->when($hasta, function ($query) use ($hasta) {
                            return $query->whereDate('fecha_hora', '<=', $hasta);
                        })
                        ->orderBy('fecha_hora', 'desc')
                        ->get();
                    
                    $pdf = PDF::loadView('exports.citas_pdf', compact('citas'));
                    Storage::put('public/reportes/' . $nombreArchivo . '.pdf', $pdf->output());
                    $path = storage_path('app/public/reportes/' . $nombreArchivo . '.pdf');
                }
                break;
                
            case 'paciente':
                $paciente = Paciente::find($id);
                if (!$paciente) {
                    $this->error('Paciente no encontrado.');
                    return 1;
                }
                
                $citas = Cita::with(['paciente', 'medico'])
                    ->where('paciente_id', $id)
                    ->when($desde, function ($query) use ($desde) {
                        return $query->whereDate('fecha_hora', '>=', $desde);
                    })
                    ->when($hasta, function ($query) use ($hasta) {
                        return $query->whereDate('fecha_hora', '<=', $hasta);
                    })
                    ->orderBy('fecha_hora', 'desc')
                    ->get();
                
                if ($formato === 'excel') {
                    Excel::store(new CitasExport($citas), 'public/reportes/' . $nombreArchivo . '.xlsx');
                    $path = storage_path('app/public/reportes/' . $nombreArchivo . '.xlsx');
                } else {
                    $pdf = PDF::loadView('exports.citas_pdf', compact('citas'));
                    Storage::put('public/reportes/' . $nombreArchivo . '.pdf', $pdf->output());
                    $path = storage_path('app/public/reportes/' . $nombreArchivo . '.pdf');
                }
                break;
                
            default: // general
                $citas = Cita::with(['paciente', 'medico'])
                    ->when($desde, function ($query) use ($desde) {
                        return $query->whereDate('fecha_hora', '>=', $desde);
                    })
                    ->when($hasta, function ($query) use ($hasta) {
                        return $query->whereDate('fecha_hora', '<=', $hasta);
                    })
                    ->orderBy('fecha_hora', 'desc')
                    ->get();
                
                if ($formato === 'excel') {
                    Excel::store(new CitasExport($citas), 'public/reportes/' . $nombreArchivo . '.xlsx');
                    $path = storage_path('app/public/reportes/' . $nombreArchivo . '.xlsx');
                } else {
                    $pdf = PDF::loadView('exports.citas_pdf', compact('citas'));
                    Storage::put('public/reportes/' . $nombreArchivo . '.pdf', $pdf->output());
                    $path = storage_path('app/public/reportes/' . $nombreArchivo . '.pdf');
                }
                break;
        }
        
        $this->info('Reporte generado exitosamente: ' . $path);
        return 0;
    }
}