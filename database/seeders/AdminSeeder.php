<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear un administrador por defecto
        Admin::create([
            'nombre' => 'Administrador',
            'email' => 'admin@example.com',
            'password' => 'password',
            'foto' => null,
        ]);
        
        // Crear 5 administradores adicionales
        for ($i = 1; $i <= 5; $i++) {
            Admin::create([
                'nombre' => 'Admin ' . $i,
                'email' => 'admin' . $i . '@example.com',
                'password' => 'password',
                'foto' => null,
            ]);
        }
    }
}
