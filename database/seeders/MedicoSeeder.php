<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MedicoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $especialidades = [
            'Cardiología',
            'Dermatología',
            'Ginecología',
            'Neurología',
            'Oftalmología',
            'Pediatría',
            'Psiquiatría',
            'Traumatología',
            'Urología',
            'Medicina General'
        ];

        for ($i = 1; $i <= 10; $i++) {
            $horaInicio = rand(8, 10) . ':00:00';
            $horaFin = rand(16, 19) . ':00:00';
            
            DB::table('medicos')->insert([
                'nombre' => 'Dr. ' . Str::random(10),
                'email' => 'medico' . $i . '@example.com',
                'password' => Hash::make('password'),
                'especialidad' => $especialidades[array_rand($especialidades)],
                'horario_inicio' => $horaInicio,
                'horario_fin' => $horaFin,
                'foto' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}