<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\Cargo;
use App\Models\EquipoTrabajo;
use App\Models\Especializacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmpleadoController extends Controller
{
    public function index()
    {
        $empleados = Empleado::with([
                'cargo', 
                'equipo', 
                'especializacion',
                'tareas' => function($query) {
                    $query->select('no_tarea', 'empleado_no', 'estado_tarea')
                          ->with('estado'); // Cargamos la relación estado
                }
            ])
            ->withCount('tareas')
            ->get();
    
        return view('empleados.index', compact('empleados'));
    }

    public function create()
    {
        $cargos = Cargo::select('no_cargo', 'nombre')->orderBy('nombre')->get();
        $equipos = EquipoTrabajo::select('no_equipo', 'nombre')->orderBy('nombre')->get();
        $especializaciones = Especializacion::select('no_especializacion', 'nombre')->orderBy('nombre')->get();

        return view('empleados.create', compact('cargos', 'equipos', 'especializaciones'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:64',
            'telefono' => 'required|digits:8|unique:empleado,telefono',
            'correo' => 'required|email|max:64|unique:empleado,correo',
            'direccion' => 'required|string|max:100',
            'no_equipo' => 'required|exists:equipo_trabajo,no_equipo',
            'no_especializacion' => 'required|exists:especializacion,no_especializacion',
            'cargo_no_cargo' => 'required|exists:cargo,no_cargo'
        ]);

        try {
            DB::beginTransaction();
            $empleado = Empleado::create($validated);
            DB::commit();

            return redirect()
                ->route('empleado.index')
                ->with('success', 'Empleado creado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Error al crear el empleado: ' . $e->getMessage());
        }
    }

    public function show(Empleado $empleado)
    {
        $empleado->load([
            'cargo', 
            'equipo', 
            'especializacion', 
            'tareas' => function($query) {
                $query->with('proyecto', 'estado')
                    ->latest('fecha_fin')
                    ->take(5);
            }
        ]);
        
        $estadisticasTareas = [
            'completadas' => $empleado->tareas()
                ->whereHas('estado', function($query) {
                    $query->where('nombre', 'FINALIZADA');
                })->count(),
            'pendientes' => $empleado->tareas()
                ->whereHas('estado', function($query) {
                    $query->whereIn('nombre', ['CREADA', 'EN PROCESO']);
                })->count(),
            'total' => $empleado->tareas()->count()
        ];
    
        $cargos = Cargo::select('no_cargo', 'nombre')->orderBy('nombre')->get();
        $equipos = EquipoTrabajo::select('no_equipo', 'nombre')->orderBy('nombre')->get();
    
        return view('empleados.show', compact('empleado', 'estadisticasTareas', 'cargos', 'equipos'));
    }

    public function edit(Empleado $empleado)
    {
        $cargos = Cargo::select('no_cargo', 'nombre')->orderBy('nombre')->get();
        $equipos = EquipoTrabajo::select('no_equipo', 'nombre')->orderBy('nombre')->get();
        $especializaciones = Especializacion::select('no_especializacion', 'nombre')->orderBy('nombre')->get();

        return view('empleados.edit', compact('empleado', 'cargos', 'equipos', 'especializaciones'));
    }

    public function update(Request $request, Empleado $empleado)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:64',
            'telefono' => 'required|digits:8|unique:empleado,telefono,' . $empleado->no_empleado . ',no_empleado',
            'correo' => 'required|email|max:64|unique:empleado,correo,' . $empleado->no_empleado . ',no_empleado',
            'direccion' => 'required|string|max:100',
            'no_equipo' => 'required|exists:equipo_trabajo,no_equipo',
            'no_especializacion' => 'required|exists:especializacion,no_especializacion',
            'cargo_no_cargo' => 'required|exists:cargo,no_cargo'
        ]);

        try {
            DB::beginTransaction();
            $empleado->update($validated);
            DB::commit();

            return redirect()
                ->route('empleado.show', $empleado)
                ->with('success', 'Empleado actualizado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Error al actualizar el empleado: ' . $e->getMessage());
        }
    }

    public function destroy(Empleado $empleado)
    {
        try {
            if ($empleado->tareas()->exists()) {
                return back()->with('error', 'No se puede eliminar el empleado porque tiene tareas asignadas.');
            }

            DB::beginTransaction();
            $empleado->delete();
            DB::commit();

            return redirect()
                ->route('empleado.index')
                ->with('success', 'Empleado eliminado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al eliminar el empleado: ' . $e->getMessage());
        }
    }

    public function tareas(Empleado $empleado)
    {
        $tareas = $empleado->tareas()
            ->with(['proyecto', 'estado'])
            ->orderBy('fecha_fin')
            ->get()
            ->groupBy(function($tarea) {
                if ($tarea->estado->nombre === 'FINALIZADA') {
                    return 'completadas';
                }
                return $tarea->fecha_fin < now() ? 'atrasadas' : 'pendientes';
            });

        return view('empleados.tareas', compact('empleado', 'tareas'));
    }

    public function rendimiento(Empleado $empleado)
{
    $stats = [
        'tareas_completadas' => $empleado->tareas()
            ->whereHas('estado', function($query) {
                $query->where('nombre', 'FINALIZADA');
            })->count(),
            
        'tareas_a_tiempo' => $empleado->tareas()
            ->whereHas('estado', function($query) {
                $query->where('nombre', 'FINALIZADA');
            })
            ->where('fecha_fin', '>=', DB::raw('fecha_inicio'))
            ->count(),
            
        'proyectos_participados' => $empleado->tareas()
            ->select('no_proyecto')
            ->distinct()
            ->count(),
            
        'promedio_dias_tarea' => $empleado->tareas()
            ->whereHas('estado', function($query) {
                $query->where('nombre', 'FINALIZADA');
            })
            ->selectRaw('ROUND(AVG(fecha_fin - fecha_inicio)) as promedio')
            ->value('promedio') ?? 0
    ];

    $proyectos = $empleado->tareas()
        ->with(['proyecto', 'estado'])
        ->get()
        ->groupBy('no_proyecto')
        ->map(function($tareas) {
            $totalTareas = $tareas->count();
            $completadas = $tareas->where('estado.nombre', 'FINALIZADA')->count();
            $aTiempo = $tareas->where('estado.nombre', 'FINALIZADA')
                ->filter(function($tarea) {
                    return $tarea->fecha_fin >= $tarea->fecha_inicio;
                })->count();
            
            return [
                'proyecto' => $tareas->first()->proyecto,
                'total' => $totalTareas,
                'completadas' => $completadas,
                'a_tiempo' => $aTiempo,
                'rendimiento' => $totalTareas > 0 ? ($aTiempo / $totalTareas) * 100 : 0
            ];
        });

    return view('empleados.rendimiento', compact('empleado', 'stats', 'proyectos'));
}

    public function cambiarEquipo(Request $request, Empleado $empleado)
    {
        $validated = $request->validate([
            'no_equipo' => 'required|exists:equipo_trabajo,no_equipo'
        ]);

        try {
            DB::beginTransaction();
            $empleado->update($validated);
            DB::commit();

            return back()->with('success', 'Equipo actualizado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al cambiar el equipo del empleado.');
        }
    }

    public function asignarCargo(Request $request, Empleado $empleado)
    {
        $validated = $request->validate([
            'cargo_no_cargo' => 'required|exists:cargo,no_cargo'
        ]);

        try {
            DB::beginTransaction();
            $empleado->update($validated);
            DB::commit();

            return back()->with('success', 'Cargo actualizado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al asignar el cargo al empleado.');
        }
    }
}