@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Agendar Nueva Cita</h1>
        <a href="{{ route('citas.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
            Volver
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden p-6">
        <form action="{{ route('citas.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Selector de médico -->
                <div>
                    <label for="medico_id" class="block text-sm font-medium text-gray-700 mb-1">Médico</label>
                    <select id="medico_id" name="medico_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 @error('medico_id') border-red-500 @enderror" required>
                        <option value="">Seleccione un médico</option>
                        @foreach(\App\Models\Medico::orderBy('especialidad')->orderBy('nombre')->get() as $medico)
                            <option value="{{ $medico->id }}" {{ old('medico_id', request('medico_id')) == $medico->id ? 'selected' : '' }}>
                                {{ $medico->nombre }} - {{ $medico->especialidad }}
                            </option>
                        @endforeach
                    </select>
                    @error('medico_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Fecha y hora (solo disponibles) -->
                <div>
                    <label for="fecha_hora" class="block text-sm font-medium text-gray-700 mb-1">Fecha y Hora</label>
                    <div id="horarios_disponibles_loading" class="text-sm text-gray-500 hidden">Cargando horarios disponibles...</div>
                    <select id="fecha_hora" name="fecha_hora" class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 @error('fecha_hora') border-red-500 @enderror" required>
                        <option value="">Primero seleccione un médico</option>
                    </select>
                    @error('fecha_hora')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Motivo de la cita -->
                <div class="md:col-span-2">
                    <label for="motivo" class="block text-sm font-medium text-gray-700 mb-1">Motivo de la consulta</label>
                    <textarea id="motivo" name="motivo" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 @error('motivo') border-red-500 @enderror" required>{{ old('motivo') }}</textarea>
                    @error('motivo')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Agendar Cita
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const medicoSelect = document.getElementById('medico_id');
        const fechaHoraSelect = document.getElementById('fecha_hora');
        const loadingElement = document.getElementById('horarios_disponibles_loading');
        
        medicoSelect.addEventListener('change', function() {
            const medicoId = this.value;
            
            if (medicoId) {
                // Mostrar indicador de carga
                loadingElement.classList.remove('hidden');
                fechaHoraSelect.disabled = true;
                
                // Limpiar opciones actuales
                fechaHoraSelect.innerHTML = '<option value="">Cargando horarios...</option>';
                
                // Hacer petición AJAX para obtener horarios disponibles
                fetch(`/api/medicos/${medicoId}/horarios-disponibles`)
                    .then(response => response.json())
                    .then(data => {
                        // Ocultar indicador de carga
                        loadingElement.classList.add('hidden');
                        fechaHoraSelect.disabled = false;
                        
                        // Limpiar opciones
                        fechaHoraSelect.innerHTML = '';
                        
                        if (data.length === 0) {
                            const option = document.createElement('option');
                            option.value = '';
                            option.textContent = 'No hay horarios disponibles';
                            fechaHoraSelect.appendChild(option);
                        } else {
                            const defaultOption = document.createElement('option');
                            defaultOption.value = '';
                            defaultOption.textContent = 'Seleccione un horario';
                            fechaHoraSelect.appendChild(defaultOption);
                            
                            // Agrupar horarios por fecha
                            const horariosPorFecha = {};
                            
                            data.forEach(horario => {
                                const fecha = new Date(horario.fecha_hora);
                                const fechaStr = fecha.toLocaleDateString('es-ES', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
                                
                                if (!horariosPorFecha[fechaStr]) {
                                    horariosPorFecha[fechaStr] = [];
                                }
                                
                                horariosPorFecha[fechaStr].push({
                                    valor: horario.fecha_hora,
                                    hora: fecha.toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit' })
                                });
                            });
                            
                            // Crear grupos de opciones por fecha
                            for (const fecha in horariosPorFecha) {
                                const optgroup = document.createElement('optgroup');
                                optgroup.label = fecha.charAt(0).toUpperCase() + fecha.slice(1); // Capitalizar primera letra
                                
                                horariosPorFecha[fecha].forEach(horario => {
                                    const option = document.createElement('option');
                                    option.value = horario.valor;
                                    option.textContent = horario.hora;
                                    optgroup.appendChild(option);
                                });
                                
                                fechaHoraSelect.appendChild(optgroup);
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error al cargar horarios:', error);
                        loadingElement.classList.add('hidden');
                        fechaHoraSelect.disabled = false;
                        fechaHoraSelect.innerHTML = '<option value="">Error al cargar horarios</option>';
                    });
            } else {
                fechaHoraSelect.innerHTML = '<option value="">Primero seleccione un médico</option>';
            }
        });
        
        // Si ya hay un médico seleccionado al cargar la página, cargar sus horarios
        if (medicoSelect.value) {
            medicoSelect.dispatchEvent(new Event('change'));
        }
    });
</script>
@endsection