<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use App\Models\RegistroProceso;

class RegistroDocumento extends Model
{
    protected $table = 'registro_documentos';

    protected $fillable = [
        'registro_id',

        // Actas
        'acta_basica_path',
        'acta_basica_subida_at',
        'acta_especializada_path',
        'acta_especializada_subida_at',

        // Fotos de medición
        'foto_ph_path',
        'foto_cloro_path',

        // Fotos del proceso
        'fotos_proceso',

        // Caracterización
        'caracterizacion_completada',
        'caracterizacion_completada_at',
    ];

    protected $casts = [
        'fotos_proceso'                  => 'array',
        'caracterizacion_completada'     => 'boolean',
        'acta_basica_subida_at'          => 'datetime',
        'acta_especializada_subida_at'   => 'datetime',
        'caracterizacion_completada_at'  => 'datetime',
    ];

    // ── Relaciones ────────────────────────────────────────────────

    public function registro(): BelongsTo
    {
        return $this->belongsTo(RegistroProceso::class, 'registro_id');
    }

    // ── Helpers de URL pública ────────────────────────────────────

    public function urlActaBasica(): ?string
    {
        return $this->acta_basica_path
            ? Storage::url($this->acta_basica_path)
            : null;
    }

    public function urlActaEspecializada(): ?string
    {
        return $this->acta_especializada_path
            ? Storage::url($this->acta_especializada_path)
            : null;
    }

    public function urlFotoPh(): ?string
    {
        return $this->foto_ph_path
            ? Storage::url($this->foto_ph_path)
            : null;
    }

    public function urlFotoCloro(): ?string
    {
        return $this->foto_cloro_path
            ? Storage::url($this->foto_cloro_path)
            : null;
    }

    /**
     * Devuelve array de URLs públicas de las fotos del proceso.
     * Nunca devuelve null, siempre un array (vacío si no hay fotos).
     */
    public function urlsFotoProceso(): array
    {
        if (empty($this->fotos_proceso)) {
            return [];
        }

        return array_map(
            fn($path) => Storage::url($path),
            $this->fotos_proceso
        );
    }

    // ── Helpers de estado ────────────────────────────────────────

    /** Cuántos de los 6 ítems obligatorios están completos */
    public function totalCompletados(?bool $caracterizacionCompleta = null): int
    {
        $isCaract = $caracterizacionCompleta ?? (bool) $this->caracterizacion_completada;
        return collect([
            $isCaract,
            (bool) $this->acta_basica_path,
            (bool) $this->acta_especializada_path,
            (bool) $this->foto_ph_path,
            (bool) $this->foto_cloro_path,
            ! empty($this->fotos_proceso),
        ])->filter()->count();
    }

    /** Porcentaje de completitud 0–100 */
    public function porcentajeCompletitud(?bool $caracterizacionCompleta = null): int
    {
        return (int) round(($this->totalCompletados($caracterizacionCompleta) / 6) * 100);
    }

    /**
     * Resumen de estado de cada ítem para la vista del admin.
     * Devuelve array asociativo: ['clave' => ['label', 'completo', 'url'|null]]
     */
    /**
     * @param bool|null $caracterizacionCompleta  Sobreescribe el flag local cuando
     *                                             se conoce el estado real de la panadería.
     */
    public function resumenEstado(?bool $caracterizacionCompleta = null): array
    {
        $isCaract = $caracterizacionCompleta ?? (bool) $this->caracterizacion_completada;

        return [
            'caracterizacion' => [
                'label'    => 'Caracterización (51 preguntas)',
                'completo' => $isCaract,
                'fecha'    => $this->caracterizacion_completada_at,
                'url'      => null,
            ],
            'acta_basica' => [
                'label'    => 'Acta de inicio básica',
                'completo' => (bool) $this->acta_basica_path,
                'fecha'    => $this->acta_basica_subida_at,
                'url'      => $this->urlActaBasica(),
            ],
            'acta_especializada' => [
                'label'    => 'Acta de inicio especializada',
                'completo' => (bool) $this->acta_especializada_path,
                'fecha'    => $this->acta_especializada_subida_at,
                'url'      => $this->urlActaEspecializada(),
            ],
            'foto_ph' => [
                'label'    => 'Foto medición pH',
                'completo' => (bool) $this->foto_ph_path,
                'fecha'    => null,
                'url'      => $this->urlFotoPh(),
            ],
            'foto_cloro' => [
                'label'    => 'Foto medición cloro',
                'completo' => (bool) $this->foto_cloro_path,
                'fecha'    => null,
                'url'      => $this->urlFotoCloro(),
            ],
            'fotos_proceso' => [
                'label'    => 'Fotos del proceso (días 1, 3 y 5)',
                'completo' => ! empty($this->fotos_proceso),
                'fecha'    => null,
                'url'      => null, // múltiples, se manejan aparte
                'urls'     => $this->urlsFotoProceso(),
            ],
        ];
    }
}