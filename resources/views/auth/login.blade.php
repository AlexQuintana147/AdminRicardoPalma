@extends('layouts.app')

@section('content')
<div class="flex flex-col items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-md">
        <div class="text-center">
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                Iniciar Sesión
            </h2>
        </div>
        
        <div class="mt-8 bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
            <!-- Selector de tipo de usuario -->
            <div class="mb-6">
                <label for="userType" class="block text-sm font-medium text-gray-700 mb-2">Tipo de Usuario</label>
                <select id="userType" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                    <option value="admin">Administrador</option>
                    <option value="recepcionista">Recepcionista</option>
                    <option value="medico">Médico</option>
                    <option value="paciente">Paciente</option>
                </select>
            </div>
            
            <!-- Formularios para cada tipo de usuario -->
            <div id="adminForm" class="login-form">
                <form method="POST" action="{{ route('admin.login') }}" class="space-y-6">
                    @csrf
                    <div>
                        <label for="admin_email" class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
                        <div class="mt-1">
                            <input id="admin_email" name="email" type="email" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm" value="{{ old('email') }}">
                            @error('email', 'admin')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="admin_password" class="block text-sm font-medium text-gray-700">Contraseña</label>
                        <div class="mt-1">
                            <input id="admin_password" name="password" type="password" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm">
                            @error('password', 'admin')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="admin_remember" name="remember" type="checkbox" class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                            <label for="admin_remember" class="ml-2 block text-sm text-gray-900">Recordarme</label>
                        </div>
                    </div>

                    <div>
                        <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            Iniciar Sesión
                        </button>
                    </div>
                </form>
            </div>
            
            <div id="recepcionistaForm" class="login-form hidden">
                <form method="POST" action="{{ route('recepcionista.login') }}" class="space-y-6">
                    @csrf
                    <div>
                        <label for="recepcionista_email" class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
                        <div class="mt-1">
                            <input id="recepcionista_email" name="email" type="email" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm" value="{{ old('email') }}">
                            @error('email', 'recepcionista')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="recepcionista_password" class="block text-sm font-medium text-gray-700">Contraseña</label>
                        <div class="mt-1">
                            <input id="recepcionista_password" name="password" type="password" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm">
                            @error('password', 'recepcionista')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="recepcionista_remember" name="remember" type="checkbox" class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                            <label for="recepcionista_remember" class="ml-2 block text-sm text-gray-900">Recordarme</label>
                        </div>
                    </div>

                    <div>
                        <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            Iniciar Sesión
                        </button>
                    </div>
                </form>
            </div>
            
            <div id="medicoForm" class="login-form hidden">
                <form method="POST" action="{{ route('medico.login') }}" class="space-y-6">
                    @csrf
                    <div>
                        <label for="medico_email" class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
                        <div class="mt-1">
                            <input id="medico_email" name="email" type="email" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm" value="{{ old('email') }}">
                            @error('email', 'medico')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="medico_password" class="block text-sm font-medium text-gray-700">Contraseña</label>
                        <div class="mt-1">
                            <input id="medico_password" name="password" type="password" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm">
                            @error('password', 'medico')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="medico_remember" name="remember" type="checkbox" class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                            <label for="medico_remember" class="ml-2 block text-sm text-gray-900">Recordarme</label>
                        </div>
                    </div>

                    <div>
                        <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            Iniciar Sesión
                        </button>
                    </div>
                </form>
            </div>
            
            <div id="pacienteForm" class="login-form hidden">
                <form method="POST" action="{{ route('paciente.login') }}" class="space-y-6">
                    @csrf
                    <div>
                        <label for="paciente_email" class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
                        <div class="mt-1">
                            <input id="paciente_email" name="email" type="email" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm" value="{{ old('email') }}">
                            @error('email', 'paciente')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="paciente_password" class="block text-sm font-medium text-gray-700">Contraseña</label>
                        <div class="mt-1">
                            <input id="paciente_password" name="password" type="password" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm">
                            @error('password', 'paciente')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="paciente_remember" name="remember" type="checkbox" class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                            <label for="paciente_remember" class="ml-2 block text-sm text-gray-900">Recordarme</label>
                        </div>
                    </div>

                    <div>
                        <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            Iniciar Sesión
                        </button>
                    </div>
                </form>
            </div>
            
            <div class="mt-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">
                            ¿No tienes una cuenta?
                        </span>
                    </div>
                </div>

                <div class="mt-6">
                    <a href="{{ route('register') }}" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-green-600 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Registrarse como Paciente
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const userTypeSelect = document.getElementById('userType');
        const forms = document.querySelectorAll('.login-form');
        
        function showSelectedForm() {
            const selectedValue = userTypeSelect.value;
            
            forms.forEach(form => {
                form.classList.add('hidden');
            });
            
            document.getElementById(selectedValue + 'Form').classList.remove('hidden');
        }
        
        userTypeSelect.addEventListener('change', showSelectedForm);
        
        // Mostrar el formulario seleccionado inicialmente
        showSelectedForm();
    });
</script>
@endsection