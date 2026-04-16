<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('panaderias', function (Blueprint $table) {
            $table->string('codigo')->nullable();
            $table->string('extensionista_cedula')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('panaderias', function (Blueprint $table) {
            $table->dropColumn(['codigo', 'extensionista_cedula']);
        });
    }
};
