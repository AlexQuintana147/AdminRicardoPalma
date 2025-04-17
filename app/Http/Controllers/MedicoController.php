<?php

namespace App\Http\Controllers;

use App\Models\Medico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MedicoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $medicos = Medico::all();
        return response()->json($medicos);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:medicos',
            'password' => 'required|string|min:8',
            'especialidad' => 'required|string|max:255',
            'horario_inicio' => 'required|date_format:H:i',
            'horario_fin' => 'required|date_format:H:i|after:horario_inicio',
            'foto' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $medico = new Medico();
        $medico->nombre = $request->nombre;
        $medico->email = $request->email;
        $medico->password = $request->password;
        $medico->especialidad = $request->especialidad;
        $medico->horario_inicio = $request->horario_inicio;
        $medico->horario_fin = $request->horario_fin;

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $nombreFoto = time() . '_' . $foto->getClientOriginalName();
            $foto->storeAs('public/imageMedico', $nombreFoto);
            $medico->foto = $nombreFoto;
        }

        $medico->save();

        return response()->json(['message' => 'Médico creado con éxito', 'medico' => $medico], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Medico $medico)
    {
        return response()->json($medico);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Medico $medico)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Medico $medico)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:medicos,email,' . $medico->id,
            'password' => 'sometimes|required|string|min:8',
            'especialidad' => 'sometimes|required|string|max:255',
            'horario_inicio' => 'sometimes|required|date_format:H:i',
            'horario_fin' => 'sometimes|required|date_format:H:i|after:horario_inicio',
            'foto' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($request->has('nombre')) $medico->nombre = $request->nombre;
        if ($request->has('email')) $medico->email = $request->email;
        if ($request->has('password')) $medico->password = $request->password;
        if ($request->has('especialidad')) $medico->especialidad = $request->especialidad;
        if ($request->has('horario_inicio')) $medico->horario_inicio = $request->horario_inicio;
        if ($request->has('horario_fin')) $medico->horario_fin = $request->horario_fin;

        if ($request->hasFile('foto')) {
            // Eliminar foto anterior si existe
            if ($medico->foto) {
                Storage::delete('public/imageMedico/' . $medico->foto);
            }
            
            $foto = $request->file('foto');
            $nombreFoto = time() . '_' . $foto->getClientOriginalName();
            $foto->storeAs('public/imageMedico', $nombreFoto);
            $medico->foto = $nombreFoto;
        }

        $medico->save();

        return response()->json(['message' => 'Médico actualizado con éxito', 'medico' => $medico]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Medico $medico)
    {
        // Soft delete
        $medico->delete();
        
        return response()->json(['message' => 'Médico eliminado con éxito']);
    }

    /**
     * Verificar disponibilidad del médico en una fecha y hora específica.
     */
    public function verificarDisponibilidad(Request $request, Medico $medico)
    {
        $validator = Validator::make($request->all(), [
            'fecha' => 'required|date_format:Y-m-d',
            'hora' => 'required|date_format:H:i',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $fechaHora = \DateTime::createFromFormat('Y-m-d H:i', $request->fecha . ' ' . $request->hora);
        $disponible = $medico->isAvailable($fechaHora);

        return response()->json([
            'disponible' => $disponible,
            'horario_inicio' => $medico->horario_inicio,
            'horario_fin' => $medico->horario_fin,
        ]);
    }

    /**
     * Obtener el horario completo del médico.
     */
    public function obtenerHorario(Medico $medico)
    {
        return response()->json([
            'medico' => $medico->nombre,
            'especialidad' => $medico->especialidad,
            'horario_inicio' => $medico->horario_inicio,
            'horario_fin' => $medico->horario_fin,
        ]);
    }

    /**
     * Actualizar el horario del médico.
     */
    public function actualizarHorario(Request $request, Medico $medico)
    {
        $validator = Validator::make($request->all(), [
            'horario_inicio' => 'required|date_format:H:i',
            'horario_fin' => 'required|date_format:H:i|after:horario_inicio',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $medico->horario_inicio = $request->horario_inicio;
        $medico->horario_fin = $request->horario_fin;
        $medico->save();

        return response()->json([
            'message' => 'Horario actualizado con éxito',
            'medico' => $medico->nombre,
            'horario_inicio' => $medico->horario_inicio,
            'horario_fin' => $medico->horario_fin,
        ]);
    }
}