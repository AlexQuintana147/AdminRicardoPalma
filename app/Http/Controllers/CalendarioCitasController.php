<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CalendarioCitasController extends Controller
{
    /**
     * Muestra la vista del calendario de citas.
     */
    public function index()
    {
        return view('citas.calendario');
    }
    
    /**
     * Obtiene las citas para mostrar en el calendario.
     */
    public function getCitas(Request $request)
    {
        $query = Cita::with(['paciente', 'medico'])
            ->orderBy('fecha_hora');
        
        // Filtrar por rango de fechas
        if ($request->has('start') && $request->start) {
            $query->whereDate('fecha_hora', '>=', $request->start);
        }
        
        if ($request->has('end') && $request->end) {
            $query->whereDate('fecha_hora', '<=', $request->end);
        }
        
        // Filtrar por médico si es un médico quien consulta
        if (Auth::guard('medico')->check()) {
            $query->where('medico_id', Auth::guard('medico')->id());
        } elseif ($request->has('medico_id') && $request->medico_id) {
            $query->where('medico_id', $request->medico_id);
        }
        
        // Filtrar por estado
        if ($request->has('estado') && $request->estado) {
            $query->where('estado', $request->estado);
        }
        
        // Calcular fecha_hora_fin (30 minutos después de fecha_hora)
        $citas = $query->get()->map(function($cita) {
            $fechaInicio = Carbon::parse($cita->fecha_hora);
            $cita->fecha_hora_fin = $fechaInicio->copy()->addMinutes(30)->toDateTimeString();
            return $cita;
        });
        
        return response()->json($citas);
    }
}