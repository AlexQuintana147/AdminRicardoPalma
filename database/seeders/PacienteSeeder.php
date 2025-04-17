<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PacienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            DB::table('pacientes')->insert([
                'name' => 'Paciente ' . $i,
                'email' => 'paciente' . $i . '@example.com',
                'password' => Hash::make('password'),
                'telefono' => '9' . rand(10000000, 99999999),
                'fecha_nacimiento' => date('Y-m-d', strtotime('-' . rand(18, 70) . ' years')),
                'foto' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
