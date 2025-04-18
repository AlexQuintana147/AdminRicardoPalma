@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Detalles del Médico</h1>
        <div class="flex space-x-2">
            <a href="{{ route('medicos.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                Volver
            </a>
            @auth('admin')
            <a href="{{ route('medicos.edit', $medico) }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                Editar
            </a>
            <form action="{{ route('medicos.destroy', $medico) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded" onclick="return confirm('¿Está seguro que desea eliminar este médico?')">
                    Eliminar
                </button>
            </form>
            @endauth
        </div>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-1">
                <div class="flex flex-col items-center">
                    @if($medico->foto)
                    <img src="{{ Storage::url('public/imageMedico/' . $medico->foto) }}" alt="{{ $medico->nombre }}" class="h-48 w-48 object-cover rounded-lg shadow-md mb-4">
                    @else
                    <div class="h-48 w-48 rounded-lg bg-gray-200 flex items-center justify-center shadow-md mb-4">
                        <span class="text-gray-500 text-xl">Sin foto</span>
                    </div>
                    @endif
                    <h2 class="text-xl font-semibold text-gray-800">{{ $medico->nombre }}</h2>
                    <p class="text-gray-600 font-medium">{{ $medico->especialidad }}</p>
                </div>
            </div>
            
            <div class="md:col-span-2">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="border-b pb-2">
                        <h3 class="text-sm font-medium text-gray-500">Email</h3>
                        <p class="mt-1 text-sm text-gray-900">{{ $medico->email }}</p>
                    </div>
                    
                    <div class="border-b pb-2">
                        <h3 class="text-sm font-medium text-gray-500">Especialidad</h3>
                        <p class="mt-1 text-sm text-gray-900">{{ $medico->especialidad }}</p>
                    </div>
                    
                    <div class="border-b pb-2">
                        <h3 class="text-sm font-medium text-gray-500">Horario de Atención</h3>
                        <p class="mt-1 text-sm text-gray-900">{{ $medico->horario_inicio->format('H:i') }} - {{ $medico->horario_fin->format('H:i') }}</p>
                    </div>
                </div>
                
                <div class="mt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Próximas Citas</h3>
                    @if($medico->citas->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha y Hora</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paciente</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($medico->citas->take(5) as $cita)
                                <tr>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">{{ $cita->fecha_hora->format('d/m/Y H:i') }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">{{ $cita->paciente->name }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $cita->estado === 'Programada' ? 'bg-green-100 text-green-800' : ($cita->estado === 'Cancelada' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                            {{ $cita->estado }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p class="text-gray-500 text-sm">No hay citas programadas</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection