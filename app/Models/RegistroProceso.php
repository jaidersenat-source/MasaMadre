<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistroProceso extends Model
{
    protected $table = 'registros_proceso';

    protected $fillable = [
        'panaderia_id', 'fecha_inicio', 'hora_inicio',
        'ph_agua', 'cloro_agua', 'fecha_calibracion_tester', 'estado',
    ];

    protected function casts(): array
    {
        return [
            'fecha_inicio'              => 'date',
            'fecha_calibracion_tester'  => 'date',
        ];
    }

    public function panaderia()
    {
        return $this->belongsTo(Panaderia::class);
    }

    public function dias()
    {
        return $this->hasMany(DiaMasaMadre::class, 'registro_id')->orderBy('dia');
    }

    public function panes()
    {
        return $this->hasMany(ElaboracionPan::class, 'registro_id');
    }

    public function estaCompleto(): bool
    {
        return $this->dias()->count() >= 5 && $this->panes()->count() >= 1;
    }

    // Retorna el número del próximo día a registrar (1–5), o null si ya están todos
public function proximoDia(): ?int
{
    $diasRegistrados = $this->dias()->count();
    return $diasRegistrados < 5 ? $diasRegistrados + 1 : null;
}

// Porcentaje de progreso del proceso (0–100)
public function progreso(): int
{
    $diasRegistrados = $this->dias()->count();
    $tienePan        = $this->panes()->exists() ? 1 : 0;

    // 5 días + 1 pan = 6 pasos totales
    return (int) round((($diasRegistrados + $tienePan) / 6) * 100);
}

// Alerta si el pH del proceso va por debajo del esperado
public function alertasPh(): array
{
    $alertas = [];

    foreach ($this->dias as $dia) {
        if ($dia->ph_inicial > 5.5 && $dia->dia >= 3) {
            $alertas[] = "Día {$dia->dia}: pH inicial {$dia->ph_inicial} aún elevado.";
        }
    }

    return $alertas;
}

// En RegistroProceso.php, agregar estos scopes:

public function scopeActivos($query)
{
    return $query->where('estado', 'en_proceso');
}

public function scopeCompletados($query)
{
    return $query->where('estado', 'completado');
}

public function scopePorRegional($query, string $regional)
{
    return $query->whereHas('panaderia', fn($q) => $q->where('regional', $regional));
} 

}