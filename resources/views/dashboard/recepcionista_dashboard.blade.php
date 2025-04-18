@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Dashboard de Recepcionista</h1>
    
    <!-- Filtros -->
    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Filtros</h2>
        <form action="{{ route('dashboard.recepcionista') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Filtro por fecha -->
            <div>
                <label for="fecha" class="block text-sm font-medium text-gray-700 mb-1">Fecha</label>
                <input type="date" id="fecha" name="fecha" value="{{ request('fecha', now()->format('Y-m-d')) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50">
            </div>
            
            <!-- Filtro por especialidad -->
            <div>
                <label for="especialidad" class="block text-sm font-medium text-gray-700 mb-1">Especialidad</label>
                <select id="especialidad" name="especialidad" class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                    <option value="">Todas las especialidades</option>
                    @foreach(\App\Models\Medico::select('especialidad')->distinct()->pluck('especialidad') as $especialidad)
                        <option value="{{ $especialidad }}" {{ request('especialidad') == $especialidad ? 'selected' : '' }}>{{ $especialidad }}</option>
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
    
    <!-- Citas del día -->
    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Citas del día: {{ request('fecha', now()->format('Y-m-d')) }}</h2>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hora</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paciente</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Médico</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Especialidad</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @php
                        $fecha = request('fecha', now()->format('Y-m-d'));
                        $especialidad = request('especialidad');
                        $estado = request('estado');
                        
                        $citas = \App\Models\Cita::whereDate('fecha_hora', $fecha)
                            ->when($especialidad, function($query) use ($especialidad) {
                                return $query->whereHas('medico', function($q) use ($especialidad) {
                                    $q->where('especialidad', $especialidad);
                                });
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
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ \Carbon\Carbon::parse($cita->fecha_hora)->format('H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $cita->paciente->name }}</div>
                            <div class="text-sm text-gray-500">{{ $cita->paciente->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $cita->medico->nombre }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $cita->medico->especialidad }}
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
                                <a href="{{ route('citas.edit', $cita) }}" class="text-green-600 hover:text-green-900">Editar</a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                            No hay citas para esta fecha.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Médicos sin citas -->
    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Médicos sin citas hoy</h2>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Especialidad</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Horario</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @php
                        $fecha = request('fecha', now()->format('Y-m-d'));
                        $especialidad = request('especialidad');
                        
                        $medicosSinCitas = \App\Models\Medico::whereDoesntHave('citas', function($query) use ($fecha) {
                                return $query->whereDate('fecha_hora', $fecha);
                            })
                            ->when($especialidad, function($query) use ($especialidad) {
                                return $query->where('especialidad', $especialidad);
                            })
                            ->get();
                    @endphp
                    
                    @forelse($medicosSinCitas as $medico)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $medico->nombre }}</div>
                            <div class="text-sm text-gray-500">{{ $medico->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $medico->especialidad }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $medico->horario_inicio->format('H:i') }} - {{ $medico->horario_fin->format('H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('citas.create', ['medico_id' => $medico->id]) }}" class="text-green-600 hover:text-green-900">Agendar Cita</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                            Todos los médicos tienen citas programadas para hoy.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Gestión de Precios -->
    <div class="bg-white shadow-md rounded-lg p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Gestión de Precios</h2>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Especialidad</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio Actual</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @php
                        $especialidades = \App\Models\Medico::select('especialidad')->distinct()->get();
                    @endphp
                    
                    @foreach($especialidades as $esp)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $esp->especialidad }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            S/. {{ number_format(\App\Models\Cita::where('especialidad', $esp->especialidad)->first()->precio ?? 0, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button onclick="openPriceModal('{{ $esp->especialidad }}')" class="text-green-600 hover:text-green-900">Actualizar Precio</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal para actualizar precios -->
<div id="priceModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full" style="z-index: 50;">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modalTitle">Actualizar Precio</h3>
            <form id="updatePriceForm" action="{{ route('precios.update') }}" method="POST" class="mt-4">
                @csrf
                <input type="hidden" id="especialidad_input" name="especialidad">
                <div class="mb-4">
                    <label for="precio" class="block text-sm font-medium text-gray-700 mb-1 text-left">Nuevo Precio (S/.)</label>
                    <input type="number" step="0.01" id="precio" name="precio" class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50" required>
                </div>
                <div class="flex justify-between mt-6">
                    <button type="button" onclick="closePriceModal()" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                        Cancelar
                    </button>
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        Actualizar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openPriceModal(especialidad) {
        document.getElementById('modalTitle').textContent = 'Actualizar Precio: ' + especialidad;
        document.getElementById('especialidad_input').value = especialidad;
        document.getElementById('priceModal').classList.remove('hidden');
    }
    
    function closePriceModal() {
        document.getElementById('priceModal').classList.add('hidden');
    }
</script>
@endsection