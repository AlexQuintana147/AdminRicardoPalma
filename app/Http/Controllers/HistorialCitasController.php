<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CitasExport;

class HistorialCitasController extends Controller
{
    /**
     * Muestra el historial de citas del paciente.
     */
    public function index(Request $request)
    {
        $query = Cita::with(['paciente', 'medico'])
            ->orderBy('fecha_hora', 'desc');
        
        // Filtrar por paciente si es un paciente quien consulta
        if (Auth::guard('paciente')->check()) {
            $query->where('paciente_id', Auth::guard('paciente')->id());
        } elseif ($request->has('paciente_id')) {
            $query->where('paciente_id', $request->paciente_id);
        }
        
        // Aplicar filtros
        if ($request->has('fecha_desde') && $request->fecha_desde) {
            $query->whereDate('fecha_hora', '>=', $request->fecha_desde);
        }
        
        if ($request->has('fecha_hasta') && $request->fecha_hasta) {
            $query->whereDate('fecha_hora', '<=', $request->fecha_hasta);
        }
        
        if ($request->has('medico_id') && $request->medico_id) {
            $query->where('medico_id', $request->medico_id);
        }
        
        if ($request->has('estado') && $request->estado) {
            $query->where('estado', $request->estado);
        }
        
        $citas = $query->paginate(10);
        
        return view('citas.historial', compact('citas'));
    }
    
    /**
     * Exporta el historial de citas a PDF o Excel.
     */
    public function export(Request $request, $format)
    {
        $query = Cita::with(['paciente', 'medico'])
            ->orderBy('fecha_hora', 'desc');
        
        // Filtrar por paciente si es un paciente quien consulta
        if (Auth::guard('paciente')->check()) {
            $query->where('paciente_id', Auth::guard('paciente')->id());
        } elseif ($request->has('paciente_id')) {
            $query->where('paciente_id', $request->paciente_id);
        }
        
        // Aplicar filtros
        if ($request->has('fecha_desde') && $request->fecha_desde) {
            $query->whereDate('fecha_hora', '>=', $request->fecha_desde);
        }
        
        if ($request->has('fecha_hasta') && $request->fecha_hasta) {
            $query->whereDate('fecha_hora', '<=', $request->fecha_hasta);
        }
        
        if ($request->has('medico_id') && $request->medico_id) {
            $query->where('medico_id', $request->medico_id);
        }
        
        if ($request->has('estado') && $request->estado) {
            $query->where('estado', $request->estado);
        }
        
        $citas = $query->get();
        
        if ($format === 'pdf') {
            $pdf = PDF::loadView('exports.citas_pdf', compact('citas'));
            return $pdf->download('historial_citas.pdf');
        } elseif ($format === 'excel') {
            return Excel::download(new CitasExport($citas), 'historial_citas.xlsx');
        }
        
        return redirect()->back()->with('error', 'Formato de exportación no válido');
    }
}