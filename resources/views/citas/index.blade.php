@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Mis Citas</h1>
        <a href="{{ route('citas.create') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
            Nueva Cita
        </a>
    </div>

    <!-- Filtros -->
    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Filtros</h2>
        <form action="{{ route('citas.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Filtro por fecha -->
            <div>
                <label for="fecha" class="block text-sm font-medium text-gray-700 mb-1">Fecha</label>
                <input type="date" id="fecha" name="fecha" value="{{ request('fecha') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50">
            </div>
            
            <!-- Filtro por médico -->
            <div>
                <label for="medico_id" class="block text-sm font-medium text-gray-700 mb-1">Médico</label>
                <select id="medico_id" name="medico_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                    <option value="">Todos los médicos</option>
                    @foreach(\App\Models\Medico::orderBy('nombre')->get() as $medico)
                        <option value="{{ $medico->id }}" {{ request('medico_id') == $medico->id ? 'selected' : '' }}>{{ $medico->nombre }} ({{ $medico->especialidad }})</option>
                    @endforeach
                </select>
            </div>
            
            <!-- Filtro por estado de cita -->
            <div>
                <label for="estado" class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                <select id="estado" name="estado" class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                    <option value="">Todos los estados</option>
                    <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="confirmada" {{ request('estado') == 'confirmada' ? 'selected' : '' }}>Confirmada</option>
                    <option value="cancelada" {{ request('estado') == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                    <option value="completada" {{ request('estado') == 'completada' ? 'selected' : '' }}>Completada</option>
                </select>
            </div>
            
            <!-- Botón de filtrar -->
            <div class="flex items-end">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Filtrar
                </button>
            </div>
        </form>
    </div>

    <!-- Tabla de citas -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha y Hora</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Médico</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Especialidad</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Motivo</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @php
                    $fecha = request('fecha');
                    $medico_id = request('medico_id');
                    $estado = request('estado');
                    
                    $citas = \App\Models\Cita::query()
                        ->when(Auth::guard('paciente')->check(), function($query) {
                            return $query->where('paciente_id', Auth::guard('paciente')->id());
                        })
                        ->when(Auth::guard('medico')->check(), function($query) {
                            return $query->where('medico_id', Auth::guard('medico')->id());
                        })
                        ->when($fecha, function($query) use ($fecha) {
                            return $query->whereDate('fecha_hora', $fecha);
                        })
                        ->when($medico_id, function($query) use ($medico_id) {
                            return $query->where('medico_id', $medico_id);
                        })
                        ->when($estado, function($query) use ($estado) {
                            return $query->where('estado', $estado);
                        })
                        ->with(['paciente', 'medico'])
                        ->orderBy('fecha_hora')
                        ->get();
                @endphp
                
                @forelse($citas as $cita)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($cita->fecha_hora)->format('d/m/Y') }}</div>
                        <div class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($cita->fecha_hora)->format('H:i') }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $cita->medico->nombre }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $cita->medico->especialidad }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        {{ $cita->motivo }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $cita->estado == 'pendiente' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $cita->estado == 'confirmada' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $cita->estado == 'cancelada' ? 'bg-red-100 text-red-800' : '' }}
                            {{ $cita->estado == 'completada' ? 'bg-green-100 text-green-800' : '' }}">
                            {{ ucfirst($cita->estado) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <a href="{{ route('citas.show', $cita) }}" class="text-blue-600 hover:text-blue-900">Ver</a>
                            
                            @if($cita->estado == 'pendiente')
                                <a href="{{ route('citas.edit', $cita) }}" class="text-green-600 hover:text-green-900">Editar</a>
                                
                                <form action="{{ route('citas.update', $cita) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="estado" value="confirmada">
                                    <button type="submit" class="text-blue-600 hover:text-blue-900">Confirmar</button>
                                </form>
                                
                                <form action="{{ route('citas.update', $cita) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="estado" value="cancelada">
                                    <button type="submit" class="text-red-600 hover:text-red-900">Cancelar</button>
                                </form>
                            @endif
                            
                            @if($cita->estado == 'confirmada')
                                @if(Auth::guard('medico')->check())
                                    <form action="{{ route('citas.update', $cita) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="estado" value="completada">
                                        <button type="submit" class="text-green-600 hover:text-green-900">Marcar como atendida</button>
                                    </form>
                                @endif
                                
                                @if(Auth::guard('paciente')->check())
                                    <form action="{{ route('citas.update', $cita) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="asistio" value="1">
                                        <button type="submit" class="text-green-600 hover:text-green-900">Marcar asistencia</button>
                                    </form>
                                @endif
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                        No hay citas disponibles con los filtros seleccionados.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection