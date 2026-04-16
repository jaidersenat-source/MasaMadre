<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePanaderiaRequest;
use App\Http\Requests\UpdatePanaderiaRequest;
use App\Models\Panaderia;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PanaderiaController extends Controller
{
    // Listado con filtros
    public function index(Request $request)
    {
        $query = Panaderia::withCount(['registros', 'registros as procesos_activos_count' => function ($q) {
            $q->where('estado', 'en_proceso');
        }])->with('users');

        // Filtros opcionales
        if ($request->filled('regional')) {
            $query->where('regional', $request->regional);
        }
        if ($request->filled('estado')) {
            $query->where('activa', $request->estado === 'activa');
        }
        if ($request->filled('buscar')) {
            $query->where('nombre', 'like', '%' . $request->buscar . '%');
        }

        $panaderias = $query->latest()->paginate(15)->withQueryString();

        // Lista de regionales para el filtro (del Excel BD_CENTROS)
        $regionales = Panaderia::distinct()->pluck('regional')->sort()->values();

        return view('admin.panaderias.index', compact('panaderias', 'regionales'));
    }

    // Formulario de creación
    public function create()
    {
        $regionales = $this->listaRegionales();
        return view('admin.panaderias.crear', compact('regionales'));
    }

    // Guardar panadería + usuario asociado (transacción)
    public function store(StorePanaderiaRequest $request)
    {
        DB::transaction(function () use ($request) {
            $panaderia = Panaderia::create([
                'nombre'           => strtoupper($request->nombre),
                'ciudad'           => strtoupper($request->ciudad),
                'direccion'        => $request->direccion,
                'regional'         => $request->regional,
                'centro_formacion' => $request->centro_formacion,
                'codigo'           => $request->codigo,
                'extensionista'    => strtoupper($request->extensionista),
                'extensionista_cedula' => $request->extensionista_cedula,
                'activa'           => true,
            ]);

            User::create([
                'name'         => $request->user_name,
                'email'        => strtolower($request->user_email),
                'password'     => Hash::make($request->user_password),
                'role'         => 'panaderia',
                'panaderia_id' => $panaderia->id,
                'activo'       => true,
            ]);
        });

        return redirect()
            ->route('admin.panaderias.index')
            ->with('success', 'Panadería registrada exitosamente.');
    }

    // Ver detalle de una panadería
     public function show(Panaderia $panaderia)
    {
       $panaderia->load([
    'users',
    'caracterizacion',                          // ← NUEVO
    'registros' => fn($q) => $q
        ->withCount(['dias', 'panes'])
        ->with('documento')
        ->latest('fecha_inicio'),
        ]);
 
        $stats = [
            'total_procesos'     => $panaderia->registros->count(),
            'procesos_activos'   => $panaderia->registros->where('estado', 'en_proceso')->count(),
            'procesos_completos' => $panaderia->registros->where('estado', 'completado')->count(),
 
            // ── NUEVO: resumen de documentos de todos los procesos ──
            // Cuántos procesos tienen los 6 ítems al 100%
            'procesos_documentacion_completa' => $panaderia->registros
                ->filter(fn($r) => $r->documento->porcentajeCompletitud() === 100)
                ->count(),
 
            // Porcentaje promedio de documentación entre todos los procesos
            'documentacion_promedio' => $panaderia->registros->count() > 0
                ? (int) round(
                    $panaderia->registros->avg(fn($r) => $r->documento->porcentajeCompletitud())
                  )
                : 0,
        ];
 
        return view('admin.panaderias.show', compact('panaderia', 'stats'));
    }
    // Formulario de edición
    public function edit(Panaderia $panaderia)
    {
        $panaderia->load('users');
        $regionales = $this->listaRegionales();
        return view('admin.panaderias.editar', compact('panaderia', 'regionales'));
    }

    // Actualizar panadería
    public function update(UpdatePanaderiaRequest $request, Panaderia $panaderia)
    {
        DB::transaction(function () use ($request, $panaderia) {
            $panaderia->update([
                'nombre'           => strtoupper($request->nombre),
                'ciudad'           => strtoupper($request->ciudad),
                'direccion'        => $request->direccion,
                'regional'         => $request->regional,
                'centro_formacion' => $request->centro_formacion,
                'codigo'           => $request->codigo,
                'extensionista'    => strtoupper($request->extensionista),
                'extensionista_cedula' => $request->extensionista_cedula,
                'activa'           => $request->boolean('activa'),
            ]);


            // Actualizar email del usuario principal
            $user = $panaderia->users()->first();
            if ($user) {
                $user->name = $request->user_name;
                $user->email = strtolower($request->user_email);
                if ($request->filled('user_password')) {
                    $user->password = Hash::make($request->user_password);
                }
                $user->save();
            }
        });

        return redirect()
            ->route('admin.panaderias.show', $panaderia->id)
            ->with('success', 'Panadería actualizada.');
    }

    // Activar / desactivar panadería
    public function toggleEstado(Panaderia $panaderia)
    {
        $panaderia->update(['activa' => !$panaderia->activa]);

        // También desactivar/activar sus usuarios
        $panaderia->users()->update(['activo' => $panaderia->activa]);

        $estado = $panaderia->activa ? 'activada' : 'desactivada';

        return back()->with('success', "Panadería {$estado} correctamente.");
    }

    // Lista fija de regionales (del Excel BD_CENTROS)
    private function listaRegionales(): array
    {
        return [
            'AMAZONAS',
            'ANTIOQUIA',
            'ARAUCA',
            'ATLÁNTICO',
            'BOLIVAR',
            'BOYACÁ',
            'CALDAS',
            'CAQUETÁ',
            'CASANARE',
            'CAUCA',
            'CESAR',
            'CÓRDOBA',
            'CUNDINAMARCA',
            'CHOCO',
            'GUAINÍA',
            'GUAVIARE',
            'HUILA',
            'LA_GUAJIRA',
            'MAGDALENA',
            'META',
            'NARIÑO',
            'NORTE_DE_SANTANDER',
            'PUTUMAYO',
            'QUINDIO',
            'RISARALDA',
            'SAN_ANDRES',
            'SANTANDER',
            'TOLIMA',
            'VALLE_DEL_CAUCA',
            'VAUPÉS',
            'VICHADA',
            'DISTRITO_CAPITAL',
        ];
    }

    // Devuelve lista de centros para una regional (JSON)
    public function centros(Request $request)
    {
        $regional = $request->query('regional');
        if (!$regional) {
            return response()->json([], 400);
        }

        $centros = config('centros.' . strtoupper($regional), []);

        return response()->json(array_values($centros));
    }
}
