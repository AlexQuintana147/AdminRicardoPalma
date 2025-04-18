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
        Schema::table('citas', function (Blueprint $table) {
            $table->text('observaciones')->nullable()->after('urgente');
            $table->text('diagnostico')->nullable()->after('observaciones');
            $table->unsignedTinyInteger('calificacion')->nullable()->after('diagnostico');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('citas', function (Blueprint $table) {
            $table->dropColumn(['observaciones', 'diagnostico', 'calificacion']);
        });
    }
};