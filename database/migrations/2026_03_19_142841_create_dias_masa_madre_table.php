<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dias_masa_madre', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registro_id')->constrained('registros_proceso')->cascadeOnDelete();
            $table->tinyInteger('dia');                         // 1 al 5
            $table->decimal('pct_harina_trigo', 5, 2);
            $table->string('otras_harinas')->nullable();        // "NA" o descripción
            $table->decimal('pct_agua', 5, 2);
            $table->decimal('temp_agua', 4, 1);                 // °C
            $table->decimal('temp_ambiente', 4, 1);             // °C
            $table->decimal('temp_mezcla', 4, 1);               // °C
            $table->decimal('ph_inicial', 4, 2);
            $table->tinyInteger('tiempo_maduracion_horas');
            $table->text('observaciones')->nullable();
            $table->string('responsable');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dias_masa_madre');
    }
};