@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Perfil del Paciente</h1>
        <div>
            <a href="{{ route('pacientes.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded mr-2">
                Volver
            </a>
            <a href="{{ route('pacientes.edit', $paciente) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Editar
            </a>
        </div>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden p-6">
        <div class="flex flex-col md:flex-row">
            <div class="md:w-1/3 flex justify-center mb-6 md:mb-0">
                @if($paciente->foto)
                <img src="{{ Storage::url('public/imagePaciente/' . $paciente->foto) }}" alt="Foto de {{ $paciente->name }}" class="h-64 w-64 rounded-full object-cover border-4 border-green-500">
                @else
                <div class="h-64 w-64 rounded-full bg-gray-200 flex items-center justify-center border-4 border-green-500">
                    <span class="text-gray-500 text-xl">Sin foto</span>
                </div>
                @endif
            </div>
            
            <div class="md:w-2/3 md:pl-8">
                <div class="mb-4">
                    <h2 class="text-lg font-semibold text-gray-700">Información Personal</h2>
                    <div class="border-t border-gray-200 pt-2">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Nombre</p>
                                <p class="text-lg text-gray-900">{{ $paciente->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Email</p>
                                <p class="text-lg text-gray-900">{{ $paciente->email }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Teléfono</p>
                                <p class="text-lg text-gray-900">{{ $paciente->telefono }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Fecha de Nacimiento</p>
                                <p class="text-lg text-gray-900">{{ $paciente->fecha_nacimiento->format('d/m/Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mb-4">
                    <h2 class="text-lg font-semibold text-gray-700">Historial de Citas</h2>
                    <div class="border-t border-gray-200 pt-2">
                        @if($paciente->citas->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Médico</th>
                                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($paciente->citas as $cita)
                                        <tr>
                                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">{{ $cita->fecha->format('d/m/Y H:i') }}</td>
                                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">{{ $cita->medico->nombre }}</td>
                                            <td class="px-4 py-2 whitespace-nowrap text-sm">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    @if($cita->estado == 'Pendiente') bg-yellow-100 text-yellow-800 
                                                    @elseif($cita->estado == 'Completada') bg-green-100 text-green-800 
                                                    @elseif($cita->estado == 'Cancelada') bg-red-100 text-red-800 
                                                    @endif">
                                                    {{ $cita->estado }}
                                                </span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-gray-500 italic">No hay citas registradas para este paciente.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection