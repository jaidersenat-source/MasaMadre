<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('elaboracion_pan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registro_id')->constrained('registros_proceso')->cascadeOnDelete();
            $table->date('fecha_elaboracion');
            $table->time('hora_elaboracion');
            $table->string('tipo_pan');                         // Saludable, etc.
            $table->string('tipo_harina');                      // BLANCA, CENTENO, INTEGRAL…
            $table->decimal('temp_agua', 4, 1);
            $table->decimal('temp_ambiente', 4, 1);
            $table->decimal('temp_masa_madre', 4, 1);
            $table->decimal('ph_masa_madre', 4, 2);             // Debe ser < 4.2
            $table->decimal('ph_masa_antes_coccion', 4, 2);     // Debe ser < 4.8
            $table->decimal('ph_pan', 4, 2);                    // Debe ser < 5.8
            $table->decimal('temp_pan', 5, 1);                  // °C
            $table->text('observaciones')->nullable();
            $table->string('responsable');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('elaboracion_pan');
    }
};