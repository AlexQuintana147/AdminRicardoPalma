<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePacienteRequest;
use App\Http\Requests\UpdatePacienteRequest;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PacienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pacientes = Paciente::all();
        return response()->json($pacientes);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePacienteRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePacienteRequest $request)
    {
        $data = $request->validated();
        
        // Manejar la carga de la foto si existe
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $nombreFoto = time() . '_' . $foto->getClientOriginalName();
            $foto->storeAs('public/imagePaciente', $nombreFoto);
            $data['foto'] = $nombreFoto;
        }
        
        $paciente = Paciente::create($data);
        
        return response()->json([
            'message' => 'Paciente creado exitosamente',
            'paciente' => $paciente
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Paciente  $paciente
     * @return \Illuminate\Http\Response
     */
    public function show(Paciente $paciente)
    {
        return response()->json($paciente);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePacienteRequest  $request
     * @param  \App\Models\Paciente  $paciente
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePacienteRequest $request, Paciente $paciente)
    {
        $data = $request->validated();
        
        // Manejar la carga de la foto si existe
        if ($request->hasFile('foto')) {
            // Eliminar la foto anterior si existe
            if ($paciente->foto) {
                Storage::delete('public/imagePaciente/' . $paciente->foto);
            }
            
            $foto = $request->file('foto');
            $nombreFoto = time() . '_' . $foto->getClientOriginalName();
            $foto->storeAs('public/imagePaciente', $nombreFoto);
            $data['foto'] = $nombreFoto;
        }
        
        $paciente->update($data);
        
        return response()->json([
            'message' => 'Paciente actualizado exitosamente',
            'paciente' => $paciente
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Paciente  $paciente
     * @return \Illuminate\Http\Response
     */
    public function destroy(Paciente $paciente)
    {
        $paciente->delete();
        
        return response()->json([
            'message' => 'Paciente eliminado exitosamente'
        ]);
    }
}