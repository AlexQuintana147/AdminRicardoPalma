@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Listado de Médicos</h1>
        @auth('admin')
        <a href="{{ route('medicos.create') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
            Nuevo Médico
        </a>
        @endauth
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Foto</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Especialidad</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Horario</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($medicos as $medico)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($medico->foto)
                        <img src="{{ Storage::url('public/imageMedico/' . $medico->foto) }}" alt="{{ $medico->nombre }}" class="h-10 w-10 rounded-full object-cover">
                        @else
                        <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                            <span class="text-gray-500">N/A</span>
                        </div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ $medico->nombre }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $medico->especialidad }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $medico->horario_inicio->format('H:i') }} - {{ $medico->horario_fin->format('H:i') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <a href="{{ route('medicos.show', $medico) }}" class="text-blue-600 hover:text-blue-900">Ver</a>
                            @auth('admin')
                            <a href="{{ route('medicos.edit', $medico) }}" class="text-indigo-600 hover:text-indigo-900">Editar</a>
                            <form action="{{ route('medicos.destroy', $medico) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('¿Está seguro que desea eliminar este médico?')">Eliminar</button>
                            </form>
                            @endauth
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                        No hay médicos registrados
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection