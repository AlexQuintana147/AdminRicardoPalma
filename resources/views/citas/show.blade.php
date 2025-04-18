@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Detalles de la Cita</h1>
        <div class="flex space-x-2">
            <a href="{{ route('citas.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                Volver
            </a>
            @if($cita->estado == 'pendiente')
                <a href="{{ route('citas.edit', $cita) }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Editar
                </a>
            @endif
        </div>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Información de la cita -->
            <div>
                <h2 class="text-lg font-semibold text-gray-800 mb-2">Información de la Cita</h2>
                <div class="border-t border-gray-200 pt-2">
                    <div class="py-2 flex">
                        <span class="text-gray-500 w-1/3">Fecha:</span>
                        <span class="font-medium">{{ \Carbon\Carbon::parse($cita->fecha_hora)->format('d/m/Y') }}</span>
                    </div>
                    <div class="py-2 flex border-t border-gray-100">
                        <span class="text-gray-500 w-1/3">Hora:</span>
                        <span class="font-medium">{{ \Carbon\Carbon::parse($cita->fecha_hora)->format('H:i') }}</span>
                    </div>
                    <div class="py-2 flex border-t border-gray-100">
                        <span class="text-gray-500 w-1/3">Estado:</span>
                        <span class="font-medium px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $cita->estado == 'pendiente' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $cita->estado == 'confirmada' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $cita->estado == 'cancelada' ? 'bg-red-100 text-red-800' : '' }}
                            {{ $cita->estado == 'completada' ? 'bg-green-100 text-green-800' : '' }}">
                            {{ ucfirst($cita->estado) }}
                        </span>
                    </div>
                    <div class="py-2 flex border-t border-gray-100">
                        <span class="text-gray-500 w-1/3">Motivo:</span>
                        <span class="font-medium">{{ $cita->motivo }}</span>
                    </div>
                    @if($cita->asistio !== null)
                    <div class="py-2 flex border-t border-gray-100">
                        <span class="text-gray-500 w-1/3">Asistencia:</span>
                        <span class="font-medium">
                            @if($cita->asistio)
                                <span class="text-green-600">Asistió</span>
                            @else
                                <span class="text-red-600">No asistió</span>
                            @endif
                        </span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Información del médico -->
            <div>
                <h2 class="text-lg font-semibold text-gray-800 mb-2">Información del Médico</h2>
                <div class="border-t border-gray-200 pt-2">
                    <div class="py-2 flex">
                        <span class="text-gray-500 w-1/3">Nombre:</span>
                        <span class="font-medium">{{ $cita->medico->nombre }}</span>
                    </div>
                    <div class="py-2 flex border-t border-gray-100">
                        <span class="text-gray-500 w-1/3">Especialidad:</span>
                        <span class="font-medium">{{ $cita->medico->especialidad }}</span>
                    </div>
                    <div class="py-2 flex border-t border-gray-100">
                        <span class="text-gray-500 w-1/3">Email:</span>
                        <span class="font-medium">{{ $cita->medico->email }}</span>
                    </div>
                </div>
            </div>

            <!-- Información del paciente -->
            <div>
                <h2 class="text-lg font-semibold text-gray-800 mb-2">Información del Paciente</h2>
                <div class="border-t border-gray-200 pt-2">
                    <div class="py-2 flex">
                        <span class="text-gray-500 w-1/3">Nombre:</span>
                        <span class="font-medium">{{ $cita->paciente->name }}</span>
                    </div>
                    <div class="py-2 flex border-t border-gray-100">
                        <span class="text-gray-500 w-1/3">Email:</span>
                        <span class="font-medium">{{ $cita->paciente->email }}</span>
                    </div>
                </div>
            </div>
            
            <!-- Observaciones y Diagnóstico (visible para médicos y cuando existen) -->
            @if(Auth::guard('medico')->check() || $cita->observaciones || $cita->diagnostico)
            <div class="md:col-span-2">
                <h2 class="text-lg font-semibold text-gray-800 mb-2">Información Clínica</h2>
                <div class="border-t border-gray-200 pt-2">
                    @if(Auth::guard('medico')->check() && $cita->estado == 'confirmada')
                        <form action="{{ route('citas.update', $cita) }}" method="POST" class="space-y-4">
                            @csrf
                            @method('PUT')
                            <div>
                                <label for="observaciones" class="block text-sm font-medium text-gray-700 mb-1">Observaciones</label>
                                <textarea id="observaciones" name="observaciones" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50">{{ old('observaciones', $cita->observaciones) }}</textarea>
                            </div>
                            <div>
                                <label for="diagnostico" class="block text-sm font-medium text-gray-700 mb-1">Diagnóstico</label>
                                <textarea id="diagnostico" name="diagnostico" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50">{{ old('diagnostico', $cita->diagnostico) }}</textarea>
                            </div>
                            <div>
                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                    Guardar Información Clínica
                                </button>
                            </div>
                        </form>
                    @else
                        @if($cita->observaciones)
                            <div class="py-2">
                                <span class="block text-gray-500 font-medium mb-1">Observaciones:</span>
                                <p class="text-gray-800 bg-gray-50 p-3 rounded">{{ $cita->observaciones }}</p>
                            </div>
                        @endif
                        @if($cita->diagnostico)
                            <div class="py-2 mt-2">
                                <span class="block text-gray-500 font-medium mb-1">Diagnóstico:</span>
                                <p class="text-gray-800 bg-gray-50 p-3 rounded">{{ $cita->diagnostico }}</p>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
            @endif
            
            <!-- Calificación (solo visible para pacientes y citas completadas) -->
            @if(Auth::guard('paciente')->check() && $cita->estado == 'asistida')
            <div class="md:col-span-2">
                <h2 class="text-lg font-semibold text-gray-800 mb-2">Calificación de la Atención</h2>
                <div class="border-t border-gray-200 pt-4">
                    @if($cita->calificacion)
                        <div class="flex items-center mb-4">
                            <span class="text-gray-500 mr-4">Tu calificación:</span>
                            <div class="flex">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $cita->calificacion)
                                        <svg class="w-6 h-6 text-yellow-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    @else
                                        <svg class="w-6 h-6 text-gray-300" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    @endif
                                @endfor
                            </div>
                        </div>
                    @else
                        <form action="{{ route('citas.update', $cita) }}" method="POST" class="space-y-4">
                            @csrf
                            @method('PUT')
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">¿Cómo calificarías la atención recibida?</label>
                                <div class="flex space-x-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <label class="flex flex-col items-center cursor-pointer">
                                            <input type="radio" name="calificacion" value="{{ $i }}" class="sr-only peer">
                                            <svg class="w-8 h-8 text-gray-300 peer-checked:text-yellow-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                            <span class="text-sm mt-1">{{ $i }}</span>
                                        </label>
                                    @endfor
                                </div>
                            </div>
                            <div>
                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                    Enviar Calificación
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
            @endif

            <!-- Acciones disponibles -->
            <div>
                <h2 class="text-lg font-semibold text-gray-800 mb-2">Acciones</h2>
                <div class="border-t border-gray-200 pt-4">
                    <div class="flex flex-wrap gap-2">
                        @if($cita->estado == 'pendiente')
                            <form action="{{ route('citas.update', $cita) }}" method="POST" class="inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="estado" value="confirmada">
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Confirmar Cita
                                </button>
                            </form>
                            
                            <form action="{{ route('citas.update', $cita) }}" method="POST" class="inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="estado" value="cancelada">
                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                    Cancelar Cita
                                </button>
                            </form>
                        @endif
                        
                        @if($cita->estado == 'confirmada')
                            @if(Auth::guard('medico')->check())
                                <form action="{{ route('citas.update', $cita) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="estado" value="completada">
                                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                        Marcar como Atendida
                                    </button>
                                </form>
                            @endif
                            
                            @if(Auth::guard('paciente')->check())
                                <form action="{{ route('citas.update', $cita) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="asistio" value="1">
                                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                        Confirmar Asistencia
                                    </button>
                                </form>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection