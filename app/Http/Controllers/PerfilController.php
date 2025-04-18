<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Paciente;
use App\Models\Medico;
use App\Models\Recepcionista;
use App\Models\Admin;

class PerfilController extends Controller
{
    /**
     * Mostrar el formulario de perfil del usuario según su rol
     */
    public function show()
    {
        if (Auth::guard('paciente')->check()) {
            $usuario = Auth::guard('paciente')->user();
            $tipo = 'paciente';
            $carpetaImagen = 'imagePaciente';
        } elseif (Auth::guard('medico')->check()) {
            $usuario = Auth::guard('medico')->user();
            $tipo = 'medico';
            $carpetaImagen = 'imageDoctor';
        } elseif (Auth::guard('recepcionista')->check()) {
            $usuario = Auth::guard('recepcionista')->user();
            $tipo = 'recepcionista';
            $carpetaImagen = 'imageRecepcionista';
        } elseif (Auth::guard('admin')->check()) {
            $usuario = Auth::guard('admin')->user();
            $tipo = 'admin';
            $carpetaImagen = 'imageAdmin';
        } else {
            return redirect('/');
        }

        return view('perfil.show', compact('usuario', 'tipo', 'carpetaImagen'));
    }

    /**
     * Actualizar la información del perfil del usuario
     */
    public function update(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'telefono' => 'required|string|max:20',
        ]);

        if (Auth::guard('paciente')->check()) {
            $usuario = Auth::guard('paciente')->user();
            $modelo = Paciente::find($usuario->id);
            $carpetaImagen = 'imagePaciente';
        } elseif (Auth::guard('medico')->check()) {
            $usuario = Auth::guard('medico')->user();
            $modelo = Medico::find($usuario->id);
            $carpetaImagen = 'imageDoctor';
        } elseif (Auth::guard('recepcionista')->check()) {
            $usuario = Auth::guard('recepcionista')->user();
            $modelo = Recepcionista::find($usuario->id);
            $carpetaImagen = 'imageRecepcionista';
        } elseif (Auth::guard('admin')->check()) {
            $usuario = Auth::guard('admin')->user();
            $modelo = Admin::find($usuario->id);
            $carpetaImagen = 'imageAdmin';
        } else {
            return redirect('/');
        }

        // Verificar que el email no esté en uso por otro usuario
        $emailRule = 'required|email|unique:' . get_class($modelo) . ',email,' . $modelo->id;
        $request->validate(['email' => $emailRule]);

        $modelo->nombre = $request->nombre;
        $modelo->email = $request->email;
        $modelo->telefono = $request->telefono;
        $modelo->save();

        return redirect()->route('perfil.show')->with('success', 'Perfil actualizado correctamente');
    }

    /**
     * Actualizar la foto de perfil del usuario
     */
    public function updateFoto(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|max:2048',
        ]);

        if (Auth::guard('paciente')->check()) {
            $usuario = Auth::guard('paciente')->user();
            $modelo = Paciente::find($usuario->id);
            $carpetaImagen = 'imagePaciente';
        } elseif (Auth::guard('medico')->check()) {
            $usuario = Auth::guard('medico')->user();
            $modelo = Medico::find($usuario->id);
            $carpetaImagen = 'imageDoctor';
        } elseif (Auth::guard('recepcionista')->check()) {
            $usuario = Auth::guard('recepcionista')->user();
            $modelo = Recepcionista::find($usuario->id);
            $carpetaImagen = 'imageRecepcionista';
        } elseif (Auth::guard('admin')->check()) {
            $usuario = Auth::guard('admin')->user();
            $modelo = Admin::find($usuario->id);
            $carpetaImagen = 'imageAdmin';
        } else {
            return redirect('/');
        }

        // Eliminar la foto anterior si existe
        if ($modelo->foto) {
            Storage::delete('public/' . $carpetaImagen . '/' . $modelo->foto);
        }

        // Guardar la nueva foto
        $foto = $request->file('foto');
        $nombreFoto = time() . '_' . $foto->getClientOriginalName();
        $foto->storeAs('public/' . $carpetaImagen, $nombreFoto);
        $modelo->foto = $nombreFoto;
        $modelo->save();

        return redirect()->route('perfil.show')->with('success', 'Foto de perfil actualizada correctamente');
    }

    /**
     * Actualizar la contraseña del usuario
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (Auth::guard('paciente')->check()) {
            $usuario = Auth::guard('paciente')->user();
            $modelo = Paciente::find($usuario->id);
        } elseif (Auth::guard('medico')->check()) {
            $usuario = Auth::guard('medico')->user();
            $modelo = Medico::find($usuario->id);
        } elseif (Auth::guard('recepcionista')->check()) {
            $usuario = Auth::guard('recepcionista')->user();
            $modelo = Recepcionista::find($usuario->id);
        } elseif (Auth::guard('admin')->check()) {
            $usuario = Auth::guard('admin')->user();
            $modelo = Admin::find($usuario->id);
        } else {
            return redirect('/');
        }

        // Verificar que la contraseña actual sea correcta
        if (!Hash::check($request->current_password, $modelo->password)) {
            return back()->withErrors(['current_password' => 'La contraseña actual no es correcta']);
        }

        $modelo->password = Hash::make($request->password);
        $modelo->save();

        return redirect()->route('perfil.show')->with('success', 'Contraseña actualizada correctamente');
    }
}