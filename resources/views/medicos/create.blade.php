@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Nuevo Médico</h1>
        <a href="{{ route('medicos.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
            Volver
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden p-6">
        <form action="{{ route('medicos.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nombre -->
                <div>
                    <label for="nombre" class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                    <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 @error('nombre') border-red-500 @enderror">
                    @error('nombre')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Contraseña -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
                    <input type="password" name="password" id="password" class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 @error('password') border-red-500 @enderror">
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Especialidad -->
                <div>
                    <label for="especialidad" class="block text-sm font-medium text-gray-700 mb-1">Especialidad</label>
                    <input type="text" name="especialidad" id="especialidad" value="{{ old('especialidad') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 @error('especialidad') border-red-500 @enderror">
                    @error('especialidad')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Horario Inicio -->
                <div>
                    <label for="horario_inicio" class="block text-sm font-medium text-gray-700 mb-1">Horario de Inicio</label>
                    <input type="time" name="horario_inicio" id="horario_inicio" value="{{ old('horario_inicio') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 @error('horario_inicio') border-red-500 @enderror">
                    @error('horario_inicio')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Horario Fin -->
                <div>
                    <label for="horario_fin" class="block text-sm font-medium text-gray-700 mb-1">Horario de Fin</label>
                    <input type="time" name="horario_fin" id="horario_fin" value="{{ old('horario_fin') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 @error('horario_fin') border-red-500 @enderror">
                    @error('horario_fin')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Foto -->
                <div class="md:col-span-2">
                    <label for="foto" class="block text-sm font-medium text-gray-700 mb-1">Foto</label>
                    <input type="file" name="foto" id="foto" class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 @error('foto') border-red-500 @enderror">
                    @error('foto')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-gray-500 text-xs mt-1">Formatos permitidos: JPG, PNG, GIF. Tamaño máximo: 2MB</p>
                </div>
            </div>

            <div class="mt-6">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Crear Médico
                </button>
            </div>
        </form>
    </div>
</div>
@endsection