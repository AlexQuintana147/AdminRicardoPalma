<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name', 'Clínica Ricardo Palma') }}</title>
    
    <!-- Tailwind CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    <!-- Header -->
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <!-- Logo -->
                    <div class="flex-shrink-0 flex items-center">
                        <a href="{{ url('/') }}" class="text-xl font-bold text-green-600">
                            Clínica Ricardo Palma
                        </a>
                    </div>
                </div>
                
                <!-- Navigation Links -->
                <div class="hidden sm:ml-6 sm:flex sm:items-center">
                    <div class="flex space-x-4">
                        @auth('admin')
                            <a href="{{ url('/admins') }}" class="text-gray-700 hover:text-green-600 px-3 py-2 rounded-md text-sm font-medium">Administradores</a>
                            <a href="{{ url('/recepcionistas') }}" class="text-gray-700 hover:text-green-600 px-3 py-2 rounded-md text-sm font-medium">Recepcionistas</a>
                            <a href="{{ url('/medicos') }}" class="text-gray-700 hover:text-green-600 px-3 py-2 rounded-md text-sm font-medium">Médicos</a>
                            <a href="{{ url('/pacientes') }}" class="text-gray-700 hover:text-green-600 px-3 py-2 rounded-md text-sm font-medium">Pacientes</a>
                            <a href="{{ url('/citas') }}" class="text-gray-700 hover:text-green-600 px-3 py-2 rounded-md text-sm font-medium">Citas</a>
                        @endauth
                        
                        @auth('recepcionista')
                            <a href="{{ url('/pacientes') }}" class="text-gray-700 hover:text-green-600 px-3 py-2 rounded-md text-sm font-medium">Pacientes</a>
                            <a href="{{ url('/medicos') }}" class="text-gray-700 hover:text-green-600 px-3 py-2 rounded-md text-sm font-medium">Médicos</a>
                            <a href="{{ url('/citas') }}" class="text-gray-700 hover:text-green-600 px-3 py-2 rounded-md text-sm font-medium">Citas</a>
                        @endauth
                        
                        @auth('medico')
                            <a href="{{ url('/citas') }}" class="text-gray-700 hover:text-green-600 px-3 py-2 rounded-md text-sm font-medium">Mis Citas</a>
                        @endauth
                        
                        @auth('paciente')
                            <a href="{{ url('/medicos') }}" class="text-gray-700 hover:text-green-600 px-3 py-2 rounded-md text-sm font-medium">Médicos</a>
                            <a href="{{ url('/citas') }}" class="text-gray-700 hover:text-green-600 px-3 py-2 rounded-md text-sm font-medium">Mis Citas</a>
                            <a href="{{ url('/citas/create') }}" class="text-gray-700 hover:text-green-600 px-3 py-2 rounded-md text-sm font-medium">Nueva Cita</a>
                            <a href="{{ route('citas.historial') }}" class="text-gray-700 hover:text-green-600 px-3 py-2 rounded-md text-sm font-medium">Historial</a>
                        @endauth
                    </div>
                </div>
                
                <!-- User Menu -->
                <div class="hidden sm:ml-6 sm:flex sm:items-center">
                    @if (Auth::guard('admin')->check() || Auth::guard('recepcionista')->check() || Auth::guard('medico')->check() || Auth::guard('paciente')->check())
                        <div class="ml-3 relative">
                            <div class="flex items-center">
                                <span class="text-gray-700 mr-2">
                                    @if(Auth::guard('admin')->check())
                                        Admin: {{ Auth::guard('admin')->user()->nombre }}
                                    @elseif(Auth::guard('recepcionista')->check())
                                        Recepcionista: {{ Auth::guard('recepcionista')->user()->nombre }}
                                    @elseif(Auth::guard('medico')->check())
                                        Dr(a). {{ Auth::guard('medico')->user()->nombre }}
                                    @elseif(Auth::guard('paciente')->check())
                                        {{ Auth::guard('paciente')->user()->nombre }}
                                    @endif
                                </span>
                                <a href="{{ route('perfil.show') }}" class="text-gray-700 hover:text-green-600 px-3 py-2 rounded-md text-sm font-medium">
                                    Mi Perfil
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="text-gray-700 hover:text-red-600 px-3 py-2 rounded-md text-sm font-medium">
                                        Cerrar Sesión
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('register') }}" class="text-gray-700 hover:text-green-600 px-3 py-2 rounded-md text-sm font-medium">Registrarse</a>
                        <div class="relative ml-3">
                            <button id="loginDropdownButton" class="text-gray-700 hover:text-green-600 px-3 py-2 rounded-md text-sm font-medium">Iniciar Sesión</button>
                            <div id="loginDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10">
                                <a href="{{ route('admin.login') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Administrador</a>
                                <a href="{{ route('recepcionista.login') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Recepcionista</a>
                                <a href="{{ route('medico.login') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Médico</a>
                                <a href="{{ route('paciente.login') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Paciente</a>
                            </div>
                        </div>
                        <script>
                            document.getElementById('loginDropdownButton').addEventListener('click', function() {
                                document.getElementById('loginDropdown').classList.toggle('hidden');
                            });
                        </script>
                    @endif
                </div>
                
                <!-- Mobile menu button -->
                <div class="-mr-2 flex items-center sm:hidden">
                    <button id="mobileMenuButton" type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-green-500">
                        <span class="sr-only">Abrir menú principal</span>
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Mobile menu -->
        <div id="mobileMenu" class="hidden sm:hidden">
            <div class="pt-2 pb-3 space-y-1">
                @auth('admin')
                    <a href="{{ url('/admins') }}" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">Administradores</a>
                    <a href="{{ url('/recepcionistas') }}" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">Recepcionistas</a>
                    <a href="{{ url('/medicos') }}" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">Médicos</a>
                    <a href="{{ url('/pacientes') }}" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">Pacientes</a>
                    <a href="{{ url('/citas') }}" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">Citas</a>
                @endauth
                
                @auth('recepcionista')
                    <a href="{{ url('/pacientes') }}" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">Pacientes</a>
                    <a href="{{ url('/medicos') }}" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">Médicos</a>
                    <a href="{{ url('/citas') }}" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">Citas</a>
                @endauth
                
                @auth('medico')
                    <a href="{{ url('/citas') }}" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">Mis Citas</a>
                @endauth
                
                @auth('paciente')
                    <a href="{{ url('/medicos') }}" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">Médicos</a>
                    <a href="{{ url('/citas') }}" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">Mis Citas</a>
                    <a href="{{ url('/citas/create') }}" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">Nueva Cita</a>
                @endauth
            </div>
            
            <div class="pt-4 pb-3 border-t border-gray-200">
                @if (Auth::guard('admin')->check() || Auth::guard('recepcionista')->check() || Auth::guard('medico')->check() || Auth::guard('paciente')->check())
                    <div class="px-4">
                        <div class="text-base font-medium text-gray-800">
                            @if(Auth::guard('admin')->check())
                                Admin: {{ Auth::guard('admin')->user()->nombre }}
                            @elseif(Auth::guard('recepcionista')->check())
                                Recepcionista: {{ Auth::guard('recepcionista')->user()->nombre }}
                            @elseif(Auth::guard('medico')->check())
                                Dr(a). {{ Auth::guard('medico')->user()->nombre }}
                            @elseif(Auth::guard('paciente')->check())
                                {{ Auth::guard('paciente')->user()->nombre }}
                            @endif
                        </div>
                        <form method="POST" action="{{ route('logout') }}" class="mt-2">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">
                                Cerrar Sesión
                            </button>
                        </form>
                    </div>
                @else
                    <div class="px-4 space-y-1">
                        <a href="{{ route('register') }}" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">Registrarse</a>
                        <a href="{{ route('admin.login') }}" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">Iniciar como Administrador</a>
                        <a href="{{ route('recepcionista.login') }}" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">Iniciar como Recepcionista</a>
                        <a href="{{ route('medico.login') }}" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">Iniciar como Médico</a>
                        <a href="{{ route('paciente.login') }}" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">Iniciar como Paciente</a>
                    </div>
                @endif
            </div>
        </div>
    </header>
    
    <!-- Mensajes de sesión y errores -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                    <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Cerrar</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
                </span>
            </div>
        @endif
        
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                    <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Cerrar</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
                </span>
            </div>
        @endif
        
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">¡Error!</strong>
                <span class="block sm:inline">Por favor corrige los siguientes errores:</span>
                <ul class="list-disc ml-5 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
    
    <!-- Contenido principal -->
    <main class="flex-grow">
        <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            @yield('content')
        </div>
    </main>
    
    <!-- Footer -->
    <footer class="bg-white shadow mt-auto">
        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
            <div class="text-center text-gray-500 text-sm">
                &copy; {{ date('Y') }} Clínica Ricardo Palma. Todos los derechos reservados.
            </div>
        </div>
    </footer>
    
    <!-- Script para el menú móvil -->
    <script>
        document.getElementById('mobileMenuButton').addEventListener('click', function() {
            document.getElementById('mobileMenu').classList.toggle('hidden');
        });
    </script>
</body>
</html>