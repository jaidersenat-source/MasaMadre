<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Caracterizacion extends Model
{
    protected $table = 'caracterizaciones';

    protected $fillable = [
        'panaderia_id',
        'user_id',

        // Paso 1
        'nombres_apellidos',
        'cedula',
        'rol',
        'extensionista',
        'formalizacion',
        'tipo_documento_panaderia',
        'numero_documento_panaderia',

        // Paso 2
        'ciudad_municipio',
        'zona',
        'barrio_vereda',
        'direccion',
        'celular_contacto',
        'estrato',

        // Paso 3
        'anos_funcionamiento',
        'num_empleados',
        'empleados_18_28',
        'empleados_29_40',
        'empleados_41_55',
        'empleados_55_mas',
        'empleados_femenino',
        'empleados_masculino',
        'empleados_otro_genero',
        'empleados_no_responde',
        'mujeres_cabeza_hogar',
        'hombres_cabeza_hogar',

        // Paso 4
        'grupos_especiales',

        // Paso 5
        'edu_sin_estudios',
        'edu_primaria',
        'edu_secundaria',
        'edu_media',
        'edu_tecnico',
        'edu_tecnologo',
        'edu_pregrado',
        'edu_posgrado',

        // Paso 6
        'kilos_harina_dia',
        'tipos_pan',
        'sabe_masa_madre',
        'usa_masa_madre',
        'prefermentos',
        'recibio_transferencia',
        'pan_masa_madre_deseado',

        // Paso 7
        'expectativa_aprendizaje',
        'preocupacion_masa_madre',
        'expectativa_proyecto',

        // Paso 8
        'situacion_economica',
        'cierre_reduccion',
        'dificultad_sostener',
        'nuevas_tecnicas_ingresos',

        'paso_completado',
    ];

    protected $casts = [
        'grupos_especiales' => 'array',
        'prefermentos'      => 'array',
        'kilos_harina_dia'  => 'decimal:2',
    ];

    // ── Relaciones ─────────────────────────────────────────────────────────

    public function panaderia(): BelongsTo
    {
        return $this->belongsTo(Panaderia::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ── Helpers de estado ──────────────────────────────────────────────────

    /** ¿Está completamente diligenciada (los 8 pasos)? */
    public function estaCompleta(): bool
    {
        return $this->paso_completado >= 8;
    }

    /** Porcentaje de avance 0–100 */
    public function porcentajeAvance(): int
    {
        return (int) round(($this->paso_completado / 8) * 100);
    }

    // ── Helpers para la vista del admin ───────────────────────────────────

    /**
     * Etiqueta legible para el rol (P5)
     */
    public function rolLabel(): string
    {
        return match($this->rol) {
            'Propietario'   => 'Propietario',
            'Administrador' => 'Administrador',
            'Panadero'      => 'Panadero',
            'Vendedor'      => 'Vendedor',
            default         => $this->rol ?? '—',
        };
    }

    /**
     * Etiqueta legible para la situación económica (P48)
     */
    public function situacionEconomicaLabel(): string
    {
        return match($this->situacion_economica) {
            'Muy difícil' => '🔴 Muy difícil',
            'Difícil'     => '🟠 Difícil',
            'Estable'     => '🟡 Estable',
            'Buena'       => '🟢 Buena',
            default       => $this->situacion_economica ?? '—',
        };
    }

    /**
     * Grupos especiales con etiquetas legibles para la vista del admin.
     * Devuelve solo los grupos con valor > 0.
     * Formato: ['Tercera edad' => '3', 'Discapacidad' => '2', ...]
     */
    public function gruposEspecialesActivos(): array
    {
        $etiquetas = [
            'victima_violencia'       => 'Víctima de violencia',
            'discapacidad'            => 'Discapacidad',
            'indigena'                => 'Indígena',
            'afrocolombiana'          => 'Afrocolombiana',
            'comunidades_negras'      => 'Comunidades negras',
            'raizal'                  => 'Raizal',
            'palenquera'              => 'Palenquera',
            'privada_libertad'        => 'Privada de la libertad',
            'victima_trata'           => 'Víctima de trata de personas',
            'tercera_edad'            => 'Tercera edad',
            'adolescentes_jovenes'    => 'Adolescentes y jóvenes vulnerables',
            'adolescentes_ley_penal'  => 'Adolescentes en conflicto ley penal',
            'mujer_cabeza_hogar'      => 'Mujer cabeza de hogar',
            'reincorporacion'         => 'En reincorporación',
            'reintegracion'           => 'En reintegración',
            'victima_agente_quimico'  => 'Víctima agente químico',
            'pueblo_rom'              => 'Pueblo Rom',
            'mujeres_empresarias'     => 'Mujeres empresarias',
            'ninguna'                 => 'Ninguna de las anteriores',
        ];

        $grupos = $this->grupos_especiales ?? [];
        $activos = [];

        foreach ($etiquetas as $clave => $label) {
            $valor = $grupos[$clave] ?? '0';
            if ($valor !== '0' && $valor !== 0) {
                $activos[$label] = $valor === '6_o_mas' ? '6 o más' : $valor;
            }
        }

        return $activos;
    }

    /**
     * Resumen de nivel educativo para mostrar en el admin.
     * Devuelve array ['label' => cantidad] solo con valores > 0.
     */
    public function resumenEducativo(): array
    {
        $niveles = [
            'Sin estudios'  => $this->edu_sin_estudios,
            'Primaria'      => $this->edu_primaria,
            'Secundaria'    => $this->edu_secundaria,
            'Ed. media'     => $this->edu_media,
            'Técnico'       => $this->edu_tecnico,
            'Tecnólogo'     => $this->edu_tecnologo,
            'Pregrado'      => $this->edu_pregrado,
            'Posgrado'      => $this->edu_posgrado,
        ];

        return array_filter($niveles, fn($v) => $v > 0);
    }

    /**
     * Prefermentos como texto legible (P42)
     */
    public function prefermantosLabel(): string
    {
        $pref = $this->prefermentos ?? [];
        return empty($pref) ? '—' : implode(', ', $pref);
    }
}