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