@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Detalles del Recepcionista</h1>
        <div class="flex space-x-2">
            <a href="{{ route('recepcionistas.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                Volver
            </a>
            @auth('admin')
            <a href="{{ route('recepcionistas.edit', $recepcionista) }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                Editar
            </a>
            <form action="{{ route('recepcionistas.destroy', $recepcionista) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="return confirm('¿Está seguro de eliminar este recepcionista?')">
                    Eliminar
                </button>
            </form>
            @endauth
        </div>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h2 class="text-lg font-semibold text-gray-800 mb-2">Información Personal</h2>
                <div class="border-t border-gray-200 pt-2">
                    <div class="py-2 flex">
                        <span class="text-gray-600 w-1/3">Nombre:</span>
                        <span class="text-gray-900 font-medium">{{ $recepcionista->nombre }}</span>
                    </div>
                    <div class="py-2 flex border-t border-gray-100">
                        <span class="text-gray-600 w-1/3">Email:</span>
                        <span class="text-gray-900 font-medium">{{ $recepcionista->email }}</span>
                    </div>
                </div>
            </div>
            
            <div>
                <h2 class="text-lg font-semibold text-gray-800 mb-2">Información Adicional</h2>
                <div class="border-t border-gray-200 pt-2">
                    <div class="py-2 flex">
                        <span class="text-gray-600 w-1/3">Fecha de Registro:</span>
                        <span class="text-gray-900 font-medium">{{ $recepcionista->created_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="py-2 flex border-t border-gray-100">
                        <span class="text-gray-600 w-1/3">Última Actualización:</span>
                        <span class="text-gray-900 font-medium">{{ $recepcionista->updated_at->format('d/m/Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection