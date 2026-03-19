<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('panaderias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('ciudad');
            $table->string('direccion');
            $table->string('regional');          // Lista del Excel: HUILA, ANTIOQUIA, etc.
            $table->string('centro_formacion');
            $table->string('extensionista');
            $table->boolean('activa')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('panaderias');
    }
};