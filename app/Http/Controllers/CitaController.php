<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Medico;
use App\Models\Paciente;
use App\Http\Requests\StoreCitaRequest;
use App\Http\Requests\UpdateCitaRequest;
use App\Http\Requests\ReprogramarCitaRequest;
use App\Http\Requests\CancelarCitaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CitaController extends Controller
{
    /**
     * Constructor del controlador.
     */
    public function __construct()
    {
        $this->authorizeResource(Cita::class, 'cita');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Cita::with(['paciente', 'medico', 'recepcionista']);
        
        // Filtrar por rol del usuario
        if (Auth::guard('paciente')->check()) {
            $query->where('paciente_id', Auth::guard('paciente')->id());
        } elseif (Auth::guard('medico')->check()) {
            $query->where('medico_id', Auth::guard('medico')->id());
        }
        
        // Filtros adicionales
        if ($request->has('estado')) {
            $query->where('estado', $request->estado);
        }
        
        if ($request->has('fecha')) {
            $query->whereDate('fecha_hora', $request->fecha);
        }
        
        if ($request->has('urgente')) {
            $query->where('urgente', $request->urgente == 'true');
        }
        
        $citas = $query->orderBy('fecha_hora', 'asc')->paginate(10);
        
        return response()->json($citas);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCitaRequest $request)
    {
        $validated = $request->validated();
        
        // Verificar disponibilidad del médico
        $this->verificarDisponibilidadMedico($validated['medico_id'], $validated['fecha_hora']);
        
        // Si el usuario es un paciente, asignar automáticamente
        if (Auth::guard('paciente')->check()) {
            $validated['paciente_id'] = Auth::guard('paciente')->id();
        }
        
        // Si el usuario es un recepcionista, asignar automáticamente
        if (Auth::guard('recepcionista')->check()) {
            $validated['recepcionista_id'] = Auth::guard('recepcionista')->id();
        }
        
        $cita = Cita::create($validated);
        
        // Aquí se enviaría la notificación de nueva cita
        // event(new NuevaCitaEvent($cita));
        
        return response()->json($cita, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Cita $cita)
    {
        return response()->json($cita->load(['paciente', 'medico', 'recepcionista']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCitaRequest $request, Cita $cita)
    {
        $validated = $request->validated();
        
        // Verificar disponibilidad del médico si se cambia la fecha/hora
        if (isset($validated['fecha_hora']) && $validated['fecha_hora'] != $cita->fecha_hora) {
            $this->verificarDisponibilidadMedico(
                $validated['medico_id'] ?? $cita->medico_id, 
                $validated['fecha_hora']
            );
        }
        
        $cita->update($validated);
        
        return response()->json($cita);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cita $cita)
    {
        $cita->delete();
        
        return response()->json(null, 204);
    }
    
    /**
     * Reprogramar una cita existente.
     */
    public function reprogramar(ReprogramarCitaRequest $request, Cita $cita)
    {
        $validated = $request->validated();
        
        // Verificar disponibilidad del médico
        $this->verificarDisponibilidadMedico(
            $cita->medico_id, 
            $validated['fecha_hora']
        );
        
        // Actualizar la cita
        $cita->fecha_hora = $validated['fecha_hora'];
        $cita->estado = 'reprogramada';
        $cita->save();
        
        // Aquí se enviaría la notificación de cita reprogramada
        // event(new ReprogramadaCitaEvent($cita));
        
        return response()->json($cita);
    }
    
    /**
     * Cancelar una cita existente.
     */
    public function cancelar(CancelarCitaRequest $request, Cita $cita)
    {
        $validated = $request->validated();
        
        // Actualizar la cita
        $cita->estado = 'cancelada';
        $cita->save();
        
        // Aquí se enviaría la notificación de cita cancelada
        // event(new CanceladaCitaEvent($cita));
        
        return response()->json($cita);
    }
    
    /**
     * Confirmar una cita pendiente.
     */
    public function confirmar(Cita $cita)
    {
        $this->authorize('confirmar', $cita);
        
        // Solo se pueden confirmar citas pendientes o reprogramadas
        if (!in_array($cita->estado, ['pendiente', 'reprogramada'])) {
            return response()->json(['error' => 'Solo se pueden confirmar citas pendientes o reprogramadas'], 422);
        }
        
        $cita->estado = 'confirmada';
        $cita->save();
        
        return response()->json($cita);
    }
    
    /**
     * Marcar una cita como asistida.
     */
    public function asistir(Cita $cita)
    {
        $this->authorize('asistir', $cita);
        
        // Solo se pueden marcar como asistidas citas confirmadas
        if ($cita->estado != 'confirmada') {
            return response()->json(['error' => 'Solo se pueden marcar como asistidas citas confirmadas'], 422);
        }
        
        $cita->estado = 'asistida';
        $cita->save();
        
        return response()->json($cita);
    }
    
    /**
     * Verificar disponibilidad del médico para una fecha y hora específica.
     */
    private function verificarDisponibilidadMedico($medicoId, $fechaHora)
    {
        $fechaHora = Carbon::parse($fechaHora);
        
        // Verificar que la fecha no sea en el pasado
        if ($fechaHora->isPast()) {
            abort(422, 'No se pueden agendar citas en fechas pasadas');
        }
        
        // Verificar horario del médico
        $medico = Medico::findOrFail($medicoId);
        $horaInicio = Carbon::parse($medico->horario_inicio)->hour;
        $horaFin = Carbon::parse($medico->horario_fin)->hour;
        
        if ($fechaHora->hour < $horaInicio || $fechaHora->hour >= $horaFin) {
            abort(422, 'La hora seleccionada está fuera del horario del médico');
        }
        
        // Verificar que no haya solapamiento con otras citas
        $citasSolapadas = Cita::where('medico_id', $medicoId)
            ->whereIn('estado', ['pendiente', 'confirmada', 'reprogramada'])
            ->where('fecha_hora', $fechaHora)
            ->exists();
        
        if ($citasSolapadas) {
            abort(422, 'El médico ya tiene una cita agendada para esa fecha y hora');
        }
        
        // Aquí se podría implementar la validación de feriados
        // if (esFeriado($fechaHora)) {
        //     abort(422, 'No se pueden agendar citas en días feriados');
        // }
        
        return true;
    }
}