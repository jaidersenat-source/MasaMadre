<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ElaboracionPan extends Model
{
    protected $table = 'elaboracion_pan';

    protected $fillable = [
        'registro_id', 'fecha_elaboracion', 'hora_elaboracion',
        'tipo_pan', 'tipo_harina', 'temp_agua', 'temp_ambiente',
        'temp_masa_madre', 'ph_masa_madre', 'ph_masa_antes_coccion',
        'ph_pan', 'temp_pan', 'observaciones', 'responsable',
    ];

    protected function casts(): array
    {
        return ['fecha_elaboracion' => 'date'];
    }

    public function registro()
    {
        return $this->belongsTo(RegistroProceso::class, 'registro_id');
    }
}