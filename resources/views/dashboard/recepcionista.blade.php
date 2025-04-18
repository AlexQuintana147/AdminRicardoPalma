@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h1 class="text-2xl font-semibold text-gray-800 mb-6">Bienvenido, {{ Auth::guard('recepcionista')->user()->nombre }}</h1>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <!-- Tarjeta de citas hoy -->
                    <div class="bg-blue-50 overflow-hidden shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Citas Hoy
                            </dt>
                            <dd class="mt-1 text-3xl font-semibold text-gray-900">
                                {{ \App\Models\Cita::whereDate('fecha_hora', \Carbon\Carbon::today())->count() }}
                            </dd>
                        </div>
                    </div>
                    
                    <!-- Tarjeta de citas pendientes -->
                    <div class="bg-yellow-50 overflow-hidden shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Citas Pendientes
                            </dt>
                            <dd class="mt-1 text-3xl font-semibold text-gray-900">
                                {{ \App\Models\Cita::where('estado', 'pendiente')->count() }}
                            </dd>
                        </div>
                    </div>
                    
                    <!-- Tarjeta de citas canceladas -->
                    <div class="bg-red-50 overflow-hidden shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Citas Canceladas
                            </dt>
                            <dd class="mt-1 text-3xl font-semibold text-gray-900">
                                {{ \App\Models\Cita::where('estado', 'cancelada')->count() }}
                            </dd>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Panel de Recepcionista
                        </h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">
                            Acceso rápido a las funciones principales
                        </p>
                    </div>
                    <div class="border-t border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 p-4">
                            <a href="{{ url('/citas/create') }}" class="p-4 border rounded-lg hover:bg-gray-50">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 bg-purple-100 rounded-md p-3">
                                        <svg class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="text-lg font-medium text-gray-900">Nueva Cita</h4>
                                        <p class="text-sm text-gray-500">Crear una nueva cita</p>
                                    </div>
                                </div>
                            </a>
                            
                            <a href="{{ url('/citas') }}" class="p-4 border rounded-lg hover:bg-gray-50">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 bg-blue-100 rounded-md p-3">
                                        <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="text-lg font-medium text-gray-900">Gestionar Citas</h4>
                                        <p class="text-sm text-gray-500">Ver y editar citas</p>
                                    </div>
                                </div>
                            </a>
                            
                            <a href="{{ url('/pacientes') }}" class="p-4 border rounded-lg hover:bg-gray-50">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                                        <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="text-lg font-medium text-gray-900">Gestionar Pacientes</h4>
                                        <p class="text-sm text-gray-500">Ver y registrar pacientes</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection