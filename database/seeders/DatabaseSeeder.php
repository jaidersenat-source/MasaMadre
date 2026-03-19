<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Panaderia;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Crear panadería de prueba
        $panaderia = Panaderia::create([
            'nombre'           => 'PANADERÍA LATIN EXPRESS',
            'ciudad'           => 'NEIVA',
            'direccion'        => 'CALLE 16 SUR # 17-21',
            'regional'         => 'HUILA',
            'centro_formacion' => 'CENTRO DE LA INDUSTRIA, LA EMPRESA Y LOS SERVICIOS',
            'extensionista'    => 'CARLOS ERNESTO HERNÁNDEZ TORO',
            'activa'           => true,
        ]);

        // Usuario administrador
        User::create([
            'name'         => 'Administrador SENA',
            'email'        => 'admin@sena.edu.co',
            'password'     => Hash::make('Admin1234!'),
            'role'         => 'admin',
            'panaderia_id' => null,
            'activo'       => true,
        ]);

        // Usuario de la panadería de prueba
        User::create([
            'name'         => 'Latin Express',
            'email'        => 'latinexpress@panaderia.co',
            'password'     => Hash::make('Panaderia123!'),
            'role'         => 'panaderia',
            'panaderia_id' => $panaderia->id,
            'activo'       => true,
        ]);
    }
}