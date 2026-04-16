<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registro_documentos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('registro_id')
                ->constrained('registros_proceso')
                  ->cascadeOnDelete();

            // ── ACTAS DE COMPROMISO ───────────────────────────────
            $table->string('acta_basica_path')->nullable();        
            $table->timestamp('acta_basica_subida_at')->nullable(); 

            // Acta especializada
            $table->string('acta_especializada_path')->nullable();
            $table->timestamp('acta_especializada_subida_at')->nullable();

            // ── FOTOS DE MEDICIÓN (inicio del proceso) ─────────────
            $table->string('foto_ph_path')->nullable();
            $table->string('foto_cloro_path')->nullable();

           
            $table->json('fotos_proceso')->nullable();

            // ── CARACTERIZACIÓN ────────────────────────────────────
            // true = el panadero completó el formulario de 51 preguntas
            $table->boolean('caracterizacion_completada')->default(false);
            $table->timestamp('caracterizacion_completada_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registro_documentos');
    }
};
