<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiaMasaMadre extends Model
{
    protected $table = 'dias_masa_madre';

    protected $fillable = [
        'registro_id', 'dia', 'pct_harina_trigo', 'otras_harinas',
        'pct_agua', 'temp_agua', 'temp_ambiente', 'temp_mezcla',
        'ph_inicial', 'tiempo_maduracion_horas', 'observaciones', 'responsable',
    ];

    public function registro()
    {
        return $this->belongsTo(RegistroProceso::class, 'registro_id');
    }
}