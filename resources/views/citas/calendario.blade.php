@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Calendario de Citas</h1>
        <div class="flex space-x-2">
            <a href="{{ route('citas.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                Volver
            </a>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Filtros</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Selector de vista -->
            <div>
                <label for="calendar-view" class="block text-sm font-medium text-gray-700 mb-1">Vista</label>
                <div class="flex space-x-2">
                    <button type="button" id="dayView" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        Día
                    </button>
                    <button type="button" id="weekView" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        Semana
                    </button>
                    <button type="button" id="monthView" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        Mes
                    </button>
                </div>
            </div>
            
            @if(Auth::guard('admin')->check() || Auth::guard('recepcionista')->check())
            <!-- Filtro por médico (solo para admin/recepcionista) -->
            <div>
                <label for="medico_id" class="block text-sm font-medium text-gray-700 mb-1">Médico</label>
                <select id="medico_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                    <option value="">Todos los médicos</option>
                    @foreach(\App\Models\Medico::orderBy('nombre')->get() as $medico)
                        <option value="{{ $medico->id }}">
                            {{ $medico->nombre }} - {{ $medico->especialidad }}
                        </option>
                    @endforeach
                </select>
            </div>
            @endif
            
            <!-- Filtro por estado -->
            <div>
                <label for="estado" class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                <select id="estado" class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                    <option value="">Todos los estados</option>
                    <option value="pendiente">Pendiente</option>
                    <option value="confirmada">Confirmada</option>
                    <option value="cancelada">Cancelada</option>
                    <option value="reprogramada">Reprogramada</option>
                    <option value="asistida">Asistida</option>
                    <option value="no_asistida">No Asistida</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Calendario -->
    <div class="bg-white shadow-md rounded-lg p-6">
        <div id="calendar"></div>
    </div>

    <!-- Modal para detalles de cita -->
    <div id="citaModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full" x-data="{ open: false }" x-show="open">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">Detalles de la Cita</h3>
                <div class="mt-2 px-7 py-3" id="modal-content">
                    <!-- Contenido del modal -->
                </div>
                <div class="items-center px-4 py-3">
                    <button id="closeModal" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<!-- FullCalendar CSS -->
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css' rel='stylesheet' />
<style>
    .fc-event-pendiente {
        background-color: #FCD34D; /* Amarillo para pendiente */
        border-color: #F59E0B;
    }
    .fc-event-confirmada {
        background-color: #10B981; /* Verde para confirmada */
        border-color: #059669;
    }
    .fc-event-cancelada {
        background-color: #EF4444; /* Rojo para cancelada */
        border-color: #DC2626;
    }
    .fc-event-reprogramada {
        background-color: #60A5FA; /* Azul para reprogramada */
        border-color: #3B82F6;
    }
    .fc-event-asistida {
        background-color: #8B5CF6; /* Púrpura para asistida */
        border-color: #7C3AED;
    }
    .fc-event-no_asistida {
        background-color: #9CA3AF; /* Gris para no asistida */
        border-color: #6B7280;
    }
</style>
@endpush

@push('scripts')
<!-- FullCalendar JS -->
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/locales/es.js'></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar calendario
        const calendarEl = document.getElementById('calendar');
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'es',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: ''
            },
            selectable: true,
            editable: false,
            eventClick: function(info) {
                showEventDetails(info.event);
            },
            events: function(info, successCallback, failureCallback) {
                // Obtener filtros
                const medicoId = document.getElementById('medico_id')?.value || '';
                const estado = document.getElementById('estado')?.value || '';
                
                // Construir URL con filtros
                let url = '/api/citas-calendario';
                let params = new URLSearchParams();
                
                if (medicoId) params.append('medico_id', medicoId);
                if (estado) params.append('estado', estado);
                
                // Añadir fechas del rango visible
                params.append('start', info.startStr);
                params.append('end', info.endStr);
                
                // Realizar petición AJAX
                fetch(`${url}?${params.toString()}`)
                    .then(response => response.json())
                    .then(data => {
                        // Transformar datos para FullCalendar
                        const events = data.map(cita => ({
                            id: cita.id,
                            title: cita.paciente.nombre + ' - ' + cita.motivo,
                            start: cita.fecha_hora,
                            end: cita.fecha_hora_fin || new Date(new Date(cita.fecha_hora).getTime() + 30*60000),
                            extendedProps: {
                                paciente: cita.paciente.nombre,
                                medico: cita.medico.nombre,
                                especialidad: cita.medico.especialidad,
                                motivo: cita.motivo,
                                observaciones: cita.observaciones,
                                diagnostico: cita.diagnostico,
                                estado: cita.estado
                            },
                            className: `fc-event-${cita.estado}`
                        }));
                        
                        successCallback(events);
                    })
                    .catch(error => {
                        console.error('Error cargando eventos:', error);
                        failureCallback(error);
                    });
            }
        });
        
        calendar.render();
        
        // Cambiar vista del calendario
        document.getElementById('dayView').addEventListener('click', function() {
            calendar.changeView('timeGridDay');
        });
        
        document.getElementById('weekView').addEventListener('click', function() {
            calendar.changeView('timeGridWeek');
        });
        
        document.getElementById('monthView').addEventListener('click', function() {
            calendar.changeView('dayGridMonth');
        });
        
        // Actualizar eventos cuando cambian los filtros
        const filterElements = ['medico_id', 'estado'];
        filterElements.forEach(id => {
            const element = document.getElementById(id);
            if (element) {
                element.addEventListener('change', function() {
                    calendar.refetchEvents();
                });
            }
        });
        
        // Función para mostrar detalles de la cita
        function showEventDetails(event) {
            const modal = document.getElementById('citaModal');
            const modalContent = document.getElementById('modal-content');
            const props = event.extendedProps;
            
            // Construir contenido del modal
            let content = `
                <div class="space-y-3">
                    <p><strong>Paciente:</strong> ${props.paciente}</p>
                    <p><strong>Médico:</strong> ${props.medico}</p>
                    <p><strong>Especialidad:</strong> ${props.especialidad}</p>
                    <p><strong>Fecha:</strong> ${new Date(event.start).toLocaleString('es-ES')}</p>
                    <p><strong>Motivo:</strong> ${props.motivo}</p>
                    <p><strong>Estado:</strong> <span class="px-2 py-1 rounded text-white bg-${getStatusColor(props.estado)}">${props.estado.charAt(0).toUpperCase() + props.estado.slice(1)}</span></p>
            `;
            
            if (props.observaciones) {
                content += `<p><strong>Observaciones:</strong> ${props.observaciones}</p>`;
            }
            
            if (props.diagnostico) {
                content += `<p><strong>Diagnóstico:</strong> ${props.diagnostico}</p>`;
            }
            
            content += `
                    <div class="mt-4">
                        <a href="/citas/${event.id}" class="text-blue-600 hover:underline">Ver detalles completos</a>
                    </div>
                </div>
            `;
            
            modalContent.innerHTML = content;
            modal.classList.remove('hidden');
            
            // Cerrar modal
            document.getElementById('closeModal').addEventListener('click', function() {
                modal.classList.add('hidden');
            });
        }
        
        // Función para obtener color según estado
        function getStatusColor(estado) {
            const colors = {
                'pendiente': 'yellow-500',
                'confirmada': 'green-600',
                'cancelada': 'red-600',
                'reprogramada': 'blue-500',
                'asistida': 'purple-600',
                'no_asistida': 'gray-500'
            };
            
            return colors[estado] || 'gray-500';
        }
    });
</script>
@endpush