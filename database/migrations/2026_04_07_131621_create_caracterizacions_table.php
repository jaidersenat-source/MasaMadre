<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('caracterizaciones', function (Blueprint $table) {
            $table->id();

            // Relación con la panadería (una sola por panadería)
            $table->foreignId('panaderia_id')
                  ->unique()
                  ->constrained('panaderias')
                  ->cascadeOnDelete();

            // Quién llenó el formulario
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            // ── PASO 1: Identificación (P. 1–10) ──────────────────────────
            // P1 regional y P2 centro vienen del perfil de la panadería,
            // no se repiten aquí.
            $table->string('nombres_apellidos');           // P3
            $table->string('cedula');                      // P4
            $table->string('rol');                         // P5: Propietario|Administrador|Panadero|Vendedor
            $table->string('extensionista');               // P6
            // P7 nombre panadería viene del modelo Panaderia
            $table->string('formalizacion');               // P8: Sí|No
            $table->string('tipo_documento_panaderia');    // P9: NIT|Cedula
            $table->string('numero_documento_panaderia'); // P10

            // ── PASO 2: Ubicación (P. 11–16) ─────────────────────────────
            $table->string('ciudad_municipio');            // P11
            $table->string('zona');                        // P12: Urbana|Rural
            $table->string('barrio_vereda');               // P13
            $table->string('direccion');                   // P14
            $table->string('celular_contacto');            // P15
            $table->string('estrato');                     // P16: 1|2|3|4|5|6

            // ── PASO 3: Empleados — tiempo y edades (P. 17–28) ───────────
            $table->unsignedSmallInteger('anos_funcionamiento');       // P17
            $table->unsignedSmallInteger('num_empleados');             // P18
            $table->unsignedSmallInteger('empleados_18_28')->default(0); // P19
            $table->unsignedSmallInteger('empleados_29_40')->default(0); // P20
            $table->unsignedSmallInteger('empleados_41_55')->default(0); // P21
            $table->unsignedSmallInteger('empleados_55_mas')->default(0);// P22
            $table->unsignedSmallInteger('empleados_femenino')->default(0); // P23
            $table->unsignedSmallInteger('empleados_masculino')->default(0);// P24
            $table->unsignedSmallInteger('empleados_otro_genero')->default(0);    // P25
            $table->unsignedSmallInteger('empleados_no_responde')->default(0);    // P26
            $table->unsignedSmallInteger('mujeres_cabeza_hogar')->default(0);     // P27
            $table->unsignedSmallInteger('hombres_cabeza_hogar')->default(0);     // P28

            // ── PASO 4: Grupos especiales (P. 29) ────────────────────────
            // Matriz 19 grupos × valor (0,1,2,3,4,5,'6_o_mas')
            // Estructura JSON:
            // {
            //   "victima_violencia": "0",
            //   "discapacidad": "2",
            //   "indigena": "0",
            //   "afrocolombiana": "1",
            //   "comunidades_negras": "0",
            //   "raizal": "0",
            //   "palenquera": "0",
            //   "privada_libertad": "0",
            //   "victima_trata": "0",
            //   "tercera_edad": "3",
            //   "adolescentes_jovenes": "0",
            //   "adolescentes_ley_penal": "0",
            //   "mujer_cabeza_hogar": "0",
            //   "reincorporacion": "0",
            //   "reintegracion": "0",
            //   "victima_agente_quimico": "0",
            //   "pueblo_rom": "0",
            //   "mujeres_empresarias": "0",
            //   "ninguna": "0"
            // }
            $table->json('grupos_especiales');             // P29

            // ── PASO 5: Nivel educativo (P. 30–37) ───────────────────────
            $table->unsignedSmallInteger('edu_sin_estudios')->default(0);   // P30
            $table->unsignedSmallInteger('edu_primaria')->default(0);       // P31
            $table->unsignedSmallInteger('edu_secundaria')->default(0);     // P32
            $table->unsignedSmallInteger('edu_media')->default(0);          // P33
            $table->unsignedSmallInteger('edu_tecnico')->default(0);        // P34
            $table->unsignedSmallInteger('edu_tecnologo')->default(0);      // P35
            $table->unsignedSmallInteger('edu_pregrado')->default(0);       // P36
            $table->unsignedSmallInteger('edu_posgrado')->default(0);       // P37

            // ── PASO 6: Masa madre (P. 38–44) ────────────────────────────
            $table->decimal('kilos_harina_dia', 8, 2);    // P38
            $table->text('tipos_pan');                     // P39 texto libre
            $table->string('sabe_masa_madre');             // P40: Si|No
            $table->string('usa_masa_madre');              // P41: Si|No
            // P42: puede tener múltiples, se guarda como JSON array
            // Ej: ["Biga","Poolish"] o ["No uso prefermentos"]
            $table->json('prefermentos');                  // P42
            $table->string('recibio_transferencia');       // P43: Si|No
            $table->text('pan_masa_madre_deseado');        // P44 texto libre

            // ── PASO 7: Expectativas (P. 45–47) ──────────────────────────
            $table->text('expectativa_aprendizaje');       // P45
            $table->text('preocupacion_masa_madre');       // P46
            $table->text('expectativa_proyecto');          // P47

            // ── PASO 8: Condiciones económicas (P. 48–51) ────────────────
            $table->string('situacion_economica');         // P48: Muy difícil|Difícil|Estable|Buena
            $table->string('cierre_reduccion');            // P49: Sí|No
            $table->text('dificultad_sostener');           // P50 texto libre
            $table->string('nuevas_tecnicas_ingresos');    // P51: Sí|No|No sabe

            // Control de flujo — saber hasta qué paso llegaron
            // Útil si el panadero abandona a mitad y retoma
            $table->unsignedTinyInteger('paso_completado')->default(0); // 0–8

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('caracterizaciones');
    }
};