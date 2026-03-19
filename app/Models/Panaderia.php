<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Panaderia extends Model
{
    protected $fillable = [
        'nombre', 'ciudad', 'direccion', 'regional',
        'centro_formacion', 'extensionista', 'activa',
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

    
}

