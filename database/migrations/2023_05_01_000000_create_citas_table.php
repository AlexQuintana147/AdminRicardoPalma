<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('citas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_id')->constrained('pacientes');
            $table->foreignId('medico_id')->constrained('medicos');
            $table->foreignId('recepcionista_id')->nullable()->constrained('recepcionistas');
            $table->text('motivo');
            $table->timestamp('fecha_hora');
            $table->enum('estado', ['pendiente', 'confirmada', 'cancelada', 'reprogramada', 'asistida', 'no_asistida'])->default('pendiente');
            $table->boolean('urgente')->default(false);
            $table->timestamps();
            $table->softDeletes();
            
            // Índice para optimizar búsquedas por médico y fecha
            $table->index(['medico_id', 'fecha_hora']);
            // Índice para optimizar búsquedas por paciente
            $table->index('paciente_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};