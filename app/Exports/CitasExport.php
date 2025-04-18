<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class CitasExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $citas;

    public function __construct($citas)
    {
        $this->citas = $citas;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->citas;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Fecha',
            'Hora',
            'Médico',
            'Especialidad',
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
            $cita->medico->nombre,
            $cita->medico->especialidad,
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