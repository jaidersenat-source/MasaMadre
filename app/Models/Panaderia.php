<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Panaderia extends Model
{
    protected $fillable = [
        'nombre', 'ciudad', 'direccion', 'regional',
        'centro_formacion', 'codigo', 'extensionista', 'extensionista_cedula', 'activa',
    ];

    protected function casts(): array
    {
        return ['activa' => 'boolean'];
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function registros()
    {
        return $this->hasMany(RegistroProceso::class);
    }
    public function caracterizacion(): HasOne
    {
        return $this->hasOne(Caracterizacion::class)->withDefault();
    }
 

    public function tieneCaracterizacion(): bool
    {
        return $this->caracterizacion->estaCompleta();
    }

    
}

