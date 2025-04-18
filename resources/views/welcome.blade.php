@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<div class="relative bg-white overflow-hidden">
    <div class="max-w-7xl mx-auto">
        <div class="relative z-10 pb-8 bg-white sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32">
            <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 lg:mt-16 lg:px-8 xl:mt-20">
                <div class="sm:text-center lg:text-left">
                    <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                        <span class="block xl:inline">Cuidamos tu salud en</span>
                        <span class="block text-green-600 xl:inline">Clínica Ricardo Palma</span>
                    </h1>
                    <p class="mt-3 text-base text-gray-500 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                        Ofrecemos atención médica de calidad con profesionales altamente calificados y tecnología de vanguardia para el cuidado integral de tu salud.
                    </p>
                    <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
                        <div class="rounded-md shadow">
                            <a href="{{ route('register') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-green-600 hover:bg-green-700 md:py-4 md:text-lg md:px-10">
                                Regístrate
                            </a>
                        </div>
                        <div class="mt-3 sm:mt-0 sm:ml-3">
                            <a href="{{ route('paciente.login') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-green-700 bg-green-100 hover:bg-green-200 md:py-4 md:text-lg md:px-10">
                                Iniciar Sesión
                            </a>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2">
        <img class="h-56 w-full object-cover sm:h-72 md:h-96 lg:w-full lg:h-full" src="https://images.unsplash.com/photo-1576091160550-2173dba999ef?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1470&q=80" alt="Equipo médico">
    </div>
</div>

<!-- Servicios Section -->
<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h2 class="text-base text-green-600 font-semibold tracking-wide uppercase">Servicios</h2>
            <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">Atención médica integral</p>
            <p class="mt-4 max-w-2xl text-xl text-gray-500 lg:mx-auto">Ofrecemos una amplia gama de servicios médicos para cuidar de tu salud.</p>
        </div>

        <div class="mt-10">
            <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                <div class="pt-6">
                    <div class="flow-root bg-white rounded-lg px-6 pb-8 shadow-lg">
                        <div class="-mt-6">
                            <div>
                                <span class="inline-flex items-center justify-center p-3 bg-green-500 rounded-md shadow-lg">
                                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </span>
                            </div>
                            <h3 class="mt-8 text-lg font-medium text-gray-900 tracking-tight">Consultas Médicas</h3>
                            <p class="mt-5 text-base text-gray-500">
                                Atención médica personalizada con especialistas en diversas áreas de la medicina.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="pt-6">
                    <div class="flow-root bg-white rounded-lg px-6 pb-8 shadow-lg">
                        <div class="-mt-6">
                            <div>
                                <span class="inline-flex items-center justify-center p-3 bg-green-500 rounded-md shadow-lg">
                                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                </span>
                            </div>
                            <h3 class="mt-8 text-lg font-medium text-gray-900 tracking-tight">Exámenes Diagnósticos</h3>
                            <p class="mt-5 text-base text-gray-500">
                                Laboratorio clínico, radiología, ecografías y otros estudios para un diagnóstico preciso.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="pt-6">
                    <div class="flow-root bg-white rounded-lg px-6 pb-8 shadow-lg">
                        <div class="-mt-6">
                            <div>
                                <span class="inline-flex items-center justify-center p-3 bg-green-500 rounded-md shadow-lg">
                                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                </span>
                            </div>
                            <h3 class="mt-8 text-lg font-medium text-gray-900 tracking-tight">Atención de Emergencias</h3>
                            <p class="mt-5 text-base text-gray-500">
                                Servicio de emergencia disponible las 24 horas para atender situaciones urgentes.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Especialidades Section -->
<div class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h2 class="text-base text-green-600 font-semibold tracking-wide uppercase">Especialidades</h2>
            <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">Nuestras áreas médicas</p>
            <p class="mt-4 max-w-2xl text-xl text-gray-500 lg:mx-auto">Contamos con especialistas en diversas áreas para brindarte una atención completa.</p>
        </div>

        <div class="mt-10">
            <div class="grid grid-cols-2 gap-5 sm:grid-cols-3 lg:grid-cols-4">
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6 text-center">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Cardiología</h3>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6 text-center">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Pediatría</h3>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6 text-center">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Ginecología</h3>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6 text-center">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Traumatología</h3>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6 text-center">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Dermatología</h3>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6 text-center">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Neurología</h3>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6 text-center">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Oftalmología</h3>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6 text-center">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Odontología</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Médicos Destacados -->
<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h2 class="text-base text-green-600 font-semibold tracking-wide uppercase">Nuestro Equipo</h2>
            <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">Médicos destacados</p>
            <p class="mt-4 max-w-2xl text-xl text-gray-500 lg:mx-auto">Profesionales con amplia experiencia y dedicación al cuidado de tu salud.</p>
        </div>

        <div class="mt-10">
            <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="text-center">
                            <img class="h-32 w-32 rounded-full mx-auto" src="https://images.unsplash.com/photo-1559839734-2b71ea197ec2?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1170&q=80" alt="Doctor">
                            <h3 class="mt-6 text-lg leading-6 font-medium text-gray-900">Dr. Carlos Mendoza</h3>
                            <p class="text-sm text-green-600">Cardiología</p>
                            <p class="mt-2 text-sm text-gray-500">Especialista en cardiología con más de 15 años de experiencia.</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="text-center">
                            <img class="h-32 w-32 rounded-full mx-auto" src="https://images.unsplash.com/photo-1594824476967-48c8b964273f?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=687&q=80" alt="Doctora">
                            <h3 class="mt-6 text-lg leading-6 font-medium text-gray-900">Dra. Ana Sánchez</h3>
                            <p class="text-sm text-green-600">Pediatría</p>
                            <p class="mt-2 text-sm text-gray-500">Especialista en pediatría dedicada al cuidado de los más pequeños.</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="text-center">
                            <img class="h-32 w-32 rounded-full mx-auto" src="https://images.unsplash.com/photo-1612349317150-e413f6a5b16d?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1170&q=80" alt="Doctor">
                            <h3 class="mt-6 text-lg leading-6 font-medium text-gray-900">Dr. Luis Ramírez</h3>
                            <p class="text-sm text-green-600">Traumatología</p>
                            <p class="mt-2 text-sm text-gray-500">Especialista en traumatología y ortopedia con enfoque en medicina deportiva.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Contacto y Ubicación -->
<div class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h2 class="text-base text-green-600 font-semibold tracking-wide uppercase">Contacto</h2>
            <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">¿Cómo podemos ayudarte?</p>
            <p class="mt-4 max-w-2xl text-xl text-gray-500 lg:mx-auto">Estamos aquí para atender tus consultas y brindarte la mejor atención.</p>
        </div>

        <div class="mt-10">
            <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Información de contacto</h3>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                <span class="ml-3 text-gray-500">(01) 123-4567</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <span class="ml-3 text-gray-500">contacto@clinicaricardopalma.com</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span class="ml-3 text-gray-500">Av. Principal 123, Lima, Perú</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Horario de atención</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Lunes a Viernes</span>
                                <span class="text-gray-900">8:00 AM - 8:00 PM</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Sábados</span>
                                <span class="text-gray-900">8:00 AM - 2:00 PM</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Domingos</span>
                                <span class="text-gray-900">Cerrado</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Emergencias</span>
                                <span class="text-gray-900">24/7</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CTA Section -->
<div class="bg-green-700">
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8 lg:flex lg:items-center lg:justify-between">
        <h2 class="text-3xl font-extrabold tracking-tight text-white sm:text-4xl">
            <span class="block">¿Listo para cuidar tu salud?</span>
            <span class="block text-green-200">Agenda tu cita hoy mismo.</span>
        </h2>
        <div class="mt-8 flex lg:mt-0 lg:flex-shrink-0">
            <div class="inline-flex rounded-md shadow">
                <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-green-600 bg-white hover:bg-green-50">
                    Regístrate
                </a>
            </div>
            <div class="ml-3 inline-flex rounded-md shadow">
                <a href="{{ route('paciente.login') }}" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-green-600 hover:bg-green-500">
                    Iniciar Sesión
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
