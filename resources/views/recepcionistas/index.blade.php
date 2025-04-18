@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Listado de Recepcionistas</h1>
        @auth('admin')
        <a href="{{ route('recepcionistas.create') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
            Nuevo Recepcionista
        </a>
        @endauth
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($recepcionistas as $recepcionista)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $recepcionista->nombre }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-500">{{ $recepcionista->email }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <a href="{{ route('recepcionistas.show', $recepcionista) }}" class="text-blue-600 hover:text-blue-900">Ver</a>
                            @auth('admin')
                            <a href="{{ route('recepcionistas.edit', $recepcionista) }}" class="text-green-600 hover:text-green-900">Editar</a>
                            <form action="{{ route('recepcionistas.destroy', $recepcionista) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('¿Está seguro de eliminar este recepcionista?')">
                                    Eliminar
                                </button>
                            </form>
                            @endauth
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                        No hay recepcionistas registrados.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $recepcionistas->links() }}
    </div>
</div>
@endsection