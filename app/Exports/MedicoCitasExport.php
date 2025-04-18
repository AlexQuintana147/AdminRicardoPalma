<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;
use App\Models\Cita;
use App\Models\Medico;

class MedicoCitasExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $medico_id;
    protected $fecha_desde;
    protected $fecha_hasta;

    public function __construct($medico_id, $fecha_desde = null, $fecha_hasta = null)
    {
        $this->medico_id = $medico_id;
        $this->fecha_desde = $fecha_desde;
        $this->fecha_hasta = $fecha_hasta;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = Cita::with(['paciente', 'medico'])
            ->where('medico_id', $this->medico_id)
            ->orderBy('fecha_hora', 'desc');
        
        if ($this->fecha_desde) {
            $query->whereDate('fecha_hora', '>=', $this->fecha_desde);
        }
        
        if ($this->fecha_hasta) {
            $query->whereDate('fecha_hora', '<=', $this->fecha_hasta);
        }
        
        return $query->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Fecha',
            'Hora',
            'Paciente',
            'Motivo',
            'Estado',
            'Observaciones',
            'Diagnóstico',
            'Calificación'
        ];
    }

    /**
     * @param mixed $cita
     * @return array
     */
    public function map($cita): array
    {
        // Convertir calificación numérica a estrellas para mejor visualización
        $calificacion = 'N/A';
        if ($cita->calificacion) {
            $calificacion = str_repeat('★', $cita->calificacion) . str_repeat('☆', 5 - $cita->calificacion);
        }

        return [
            Carbon::parse($cita->fecha_hora)->format('d/m/Y'),
            Carbon::parse($cita->fecha_hora)->format('H:i'),
            $cita->paciente->name,
            $cita->motivo,
            ucfirst($cita->estado),
            $cita->observaciones ?? 'N/A',
            $cita->diagnostico ?? 'N/A',
            $calificacion
        ];
    }

    /**
     * @param Worksheet $sheet
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Estilo para la fila de encabezados
            1 => ['font' => ['bold' => true]],
        ];
    }
}