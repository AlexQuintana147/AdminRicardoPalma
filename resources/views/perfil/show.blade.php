@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6 bg-white border-b border-gray-200">
                <h1 class="text-2xl font-semibold text-gray-800 mb-6">Mi Perfil</h1>
                
                <div class="flex flex-col md:flex-row">
                    <!-- Foto de perfil -->
                    <div class="md:w-1/3 p-4">
                        <div class="text-center">
                            @if($usuario->foto)
                                <img src="{{ asset('storage/' . $carpetaImagen . '/' . $usuario->foto) }}" alt="Foto de perfil" class="w-48 h-48 rounded-full mx-auto object-cover border-4 border-green-500">
                            @else
                                <div class="w-48 h-48 rounded-full mx-auto bg-gray-300 flex items-center justify-center border-4 border-green-500">
                                    <span class="text-gray-600 text-5xl">{{ substr($usuario->nombre, 0, 1) }}</span>
                                </div>
                            @endif
                            
                            <h2 class="mt-4 text-xl font-semibold">{{ $usuario->nombre }}</h2>
                            <p class="text-gray-600">{{ ucfirst($tipo) }}</p>
                            
                            <!-- Formulario para actualizar foto -->
                            <form action="{{ route('perfil.update.foto') }}" method="POST" enctype="multipart/form-data" class="mt-4">
                                @csrf
                                @method('PUT')
                                <div class="mb-4">
                                    <label for="foto" class="block text-sm font-medium text-gray-700 mb-2">Cambiar foto de perfil</label>
                                    <input type="file" name="foto" id="foto" class="block w-full text-sm text-gray-500
                                        file:mr-4 file:py-2 file:px-4
                                        file:rounded-full file:border-0
                                        file:text-sm file:font-semibold
                                        file:bg-green-50 file:text-green-700
                                        hover:file:bg-green-100
                                    ">
                                    @error('foto')
                                        <span class="text-red-600 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                    Actualizar Foto
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Información del perfil -->
                    <div class="md:w-2/3 p-4">
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b">Información Personal</h3>
                            
                            <!-- Formulario para actualizar datos personales -->
                            <form action="{{ route('perfil.update') }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="grid grid-cols-1 gap-6">
                                    <div class="mb-4">
                                        <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">Nombre completo</label>
                                        <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $usuario->nombre) }}" class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                        @error('nombre')
                                            <span class="text-red-600 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Correo electrónico</label>
                                        <input type="email" name="email" id="email" value="{{ old('email', $usuario->email) }}" class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                        @error('email')
                                            <span class="text-red-600 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="telefono" class="block text-sm font-medium text-gray-700 mb-2">Teléfono</label>
                                        <input type="text" name="telefono" id="telefono" value="{{ old('telefono', $usuario->telefono) }}" class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                        @error('telefono')
                                            <span class="text-red-600 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="mt-6">
                                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                        Actualizar Información
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b">Cambiar Contraseña</h3>
                            
                            <!-- Formulario para cambiar contraseña -->
                            <form action="{{ route('perfil.update.password') }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="grid grid-cols-1 gap-6">
                                    <div class="mb-4">
                                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Contraseña actual</label>
                                        <input type="password" name="current_password" id="current_password" class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                        @error('current_password')
                                            <span class="text-red-600 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Nueva contraseña</label>
                                        <input type="password" name="password" id="password" class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                        @error('password')
                                            <span class="text-red-600 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirmar nueva contraseña</label>
                                        <input type="password" name="password_confirmation" id="password_confirmation" class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                </div>
                                
                                <div class="mt-6">
                                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                        Cambiar Contraseña
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection