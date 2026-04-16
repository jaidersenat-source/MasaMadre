<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dias_masa_madre', function (Blueprint $table) {
            $table->json('fotos')->nullable()->after('responsable');
        });
    }

    public function down(): void
    {
        Schema::table('dias_masa_madre', function (Blueprint $table) {
            $table->dropColumn('fotos');
        });
    }
};
