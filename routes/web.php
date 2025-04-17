<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\RecepcionistaController;
use App\Http\Controllers\MedicoController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CitaController;
use Illuminate\Support\Facades\Auth;

// Ruta principal
Route::get('/', function () {
    return view('welcome');
});

// Rutas para pacientes
Route::group(['prefix' => 'pacientes'], function () {
    Route::get('/', [PacienteController::class, 'index'])->middleware('auth:admin,recepcionista');
    Route::get('/create', [PacienteController::class, 'create'])->middleware('auth:admin,recepcionista');
    Route::post('/', [PacienteController::class, 'store'])->middleware('auth:admin,recepcionista');
    Route::get('/{paciente}', [PacienteController::class, 'show'])->middleware('auth:admin,recepcionista,paciente');
    Route::get('/{paciente}/edit', [PacienteController::class, 'edit'])->middleware('auth:admin,recepcionista,paciente');
    Route::put('/{paciente}', [PacienteController::class, 'update'])->middleware('auth:admin,recepcionista,paciente');
    Route::delete('/{paciente}', [PacienteController::class, 'destroy'])->middleware('auth:admin');
});

// Rutas para recepcionistas
Route::group(['prefix' => 'recepcionistas'], function () {
    Route::get('/', [RecepcionistaController::class, 'index'])->middleware('auth:admin');
    Route::get('/create', [RecepcionistaController::class, 'create'])->middleware('auth:admin');
    Route::post('/', [RecepcionistaController::class, 'store'])->middleware('auth:admin');
    Route::get('/{recepcionista}', [RecepcionistaController::class, 'show'])->middleware('auth:admin');
    Route::get('/{recepcionista}/edit', [RecepcionistaController::class, 'edit'])->middleware('auth:admin');
    Route::put('/{recepcionista}', [RecepcionistaController::class, 'update'])->middleware('auth:admin');
    Route::delete('/{recepcionista}', [RecepcionistaController::class, 'destroy'])->middleware('auth:admin');
});

// Rutas para médicos
Route::group(['prefix' => 'medicos'], function () {
    Route::get('/', [MedicoController::class, 'index'])->middleware('auth:admin,recepcionista,paciente');
    Route::get('/create', [MedicoController::class, 'create'])->middleware('auth:admin');
    Route::post('/', [MedicoController::class, 'store'])->middleware('auth:admin');
    Route::get('/{medico}', [MedicoController::class, 'show'])->middleware('auth:admin,recepcionista,paciente');
    Route::get('/{medico}/edit', [MedicoController::class, 'edit'])->middleware('auth:admin');
    Route::put('/{medico}', [MedicoController::class, 'update'])->middleware('auth:admin');
    Route::delete('/{medico}', [MedicoController::class, 'destroy'])->middleware('auth:admin');
});

// Rutas para administradores
Route::group(['prefix' => 'admins'], function () {
    Route::get('/', [AdminController::class, 'index'])->middleware('auth:admin');
    Route::get('/create', [AdminController::class, 'create'])->middleware('auth:admin');
    Route::post('/', [AdminController::class, 'store'])->middleware('auth:admin');
    Route::get('/{admin}', [AdminController::class, 'show'])->middleware('auth:admin');
    Route::get('/{admin}/edit', [AdminController::class, 'edit'])->middleware('auth:admin');
    Route::put('/{admin}', [AdminController::class, 'update'])->middleware('auth:admin');
    Route::delete('/{admin}', [AdminController::class, 'destroy'])->middleware('auth:admin');
});

// Rutas para citas
Route::group(['prefix' => 'citas'], function () {
    // Rutas accesibles para todos los usuarios autenticados
    Route::get('/', [CitaController::class, 'index'])->middleware('auth:admin,recepcionista,paciente,medico');
    
    // Rutas para crear citas (pacientes, recepcionistas y admins)
    Route::get('/create', [CitaController::class, 'create'])->middleware('auth:admin,recepcionista,paciente');
    Route::post('/', [CitaController::class, 'store'])->middleware('auth:admin,recepcionista,paciente');
    
    // Rutas para ver detalles de citas
    Route::get('/{cita}', [CitaController::class, 'show'])->middleware('auth:admin,recepcionista,paciente,medico');
    
    // Rutas para editar citas (solo admin y recepcionista)
    Route::get('/{cita}/edit', [CitaController::class, 'edit'])->middleware('auth:admin,recepcionista');
    Route::put('/{cita}', [CitaController::class, 'update'])->middleware('auth:admin,recepcionista');
    
    // Rutas para eliminar citas (solo admin)
    Route::delete('/{cita}', [CitaController::class, 'destroy'])->middleware('auth:admin');
});

// Rutas de autenticación
Route::group(['prefix' => 'auth'], function () {
    // Rutas de login para diferentes tipos de usuarios
    Route::get('/paciente/login', [PacienteController::class, 'showLoginForm'])->name('paciente.login');
    Route::post('/paciente/login', [PacienteController::class, 'login']);
    
    Route::get('/recepcionista/login', [RecepcionistaController::class, 'showLoginForm'])->name('recepcionista.login');
    Route::post('/recepcionista/login', [RecepcionistaController::class, 'login']);
    
    Route::get('/medico/login', [MedicoController::class, 'showLoginForm'])->name('medico.login');
    Route::post('/medico/login', [MedicoController::class, 'login']);
    
    Route::get('/admin/login', [AdminController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/admin/login', [AdminController::class, 'login']);
    
    // Ruta de logout común
    Route::post('/logout', function() {
        Auth::logout();
        return redirect('/');
    })->middleware('auth:admin,recepcionista,paciente,medico')->name('logout');
});

// Ruta de registro para pacientes
Route::get('/register', [PacienteController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [PacienteController::class, 'register']);
