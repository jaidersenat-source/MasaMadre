<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registros_proceso', function (Blueprint $table) {
            $table->id();
            $table->foreignId('panaderia_id')->constrained('panaderias')->cascadeOnDelete();
            $table->date('fecha_inicio');
            $table->time('hora_inicio');
            $table->decimal('ph_agua', 4, 2);           // Rango válido: 6.5 – 9
            $table->decimal('cloro_agua', 4, 2);         // Rango válido: 0.3 – 2
            $table->date('fecha_calibracion_tester')->nullable();
            $table->enum('estado', ['en_proceso', 'completado'])->default('en_proceso');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registros_proceso');
    }
};