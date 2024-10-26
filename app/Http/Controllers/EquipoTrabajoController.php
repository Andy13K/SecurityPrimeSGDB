<?php

namespace App\Http\Controllers;

use App\Models\EquipoTrabajo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EquipoTrabajoController extends Controller
{
    public function index()
    {
        $equipos = EquipoTrabajo::withCount(['empleados', 'proyectos'])
            ->get();

        return view('equipos.index', compact('equipos'));
    }

    public function create()
    {
        $empleados = \App\Models\Empleado::orderBy('nombre')
            ->get();
        return view('equipos.create', compact('empleados'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:64',
            'supervisor' => 'required|string|max:64'
        ]);

        try {
            EquipoTrabajo::create($request->all());
            return redirect()->route('equipo_trabajo.index')
                           ->with('success', 'Equipo creado exitosamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al crear el equipo: ' . $e->getMessage());
        }
    }

    public function show(EquipoTrabajo $equipo_trabajo)
    {
        $equipo_trabajo->load(['empleados.cargo', 'proyectos' => function($query) {
            $query->whereHas('estado', function($q) {
                $q->whereIn('nombre', ['CREADO', 'EN PROCESO']);
            });
        }]);

        return view('equipos.show', compact('equipo_trabajo'));
    }

    public function edit(EquipoTrabajo $equipo_trabajo)
    {
        return view('equipos.edit', compact('equipo_trabajo'));
    }

    public function update(Request $request, EquipoTrabajo $equipo_trabajo)
    {
        $request->validate([
            'nombre' => 'required|string|max:64',
            'supervisor' => 'required|string|max:64'
        ]);

        try {
            $equipo_trabajo->update($request->all());
            return redirect()->route('equipo_trabajo.show', $equipo_trabajo)
                           ->with('success', 'Equipo actualizado exitosamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al actualizar el equipo: ' . $e->getMessage());
        }
    }

    public function destroy(EquipoTrabajo $equipo_trabajo)
    {
        try {
            if ($equipo_trabajo->empleados()->exists()) {
                return back()->with('error', 'No se puede eliminar el equipo porque tiene empleados asignados.');
            }

            if ($equipo_trabajo->proyectos()->exists()) {
                return back()->with('error', 'No se puede eliminar el equipo porque tiene proyectos asignados.');
            }

            $equipo_trabajo->delete();
            return redirect()->route('equipo_trabajo.index')
                           ->with('success', 'Equipo eliminado exitosamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar el equipo: ' . $e->getMessage());
        }
    }

    // Métodos adicionales

    public function rendimiento(EquipoTrabajo $equipo_trabajo)
    {
        $stats = [
            'proyectos_completados' => $equipo_trabajo->proyectos()
                ->whereHas('estado', function($query) {
                    $query->where('nombre', 'FINALIZADO');
                })->count(),
                
            'tareas_completadas' => DB::table('tarea')
                ->join('empleado', 'tarea.empleado_no', '=', 'empleado.no_empleado')
                ->join('estado_tarea', 'tarea.estado_tarea', '=', 'estado_tarea.no_estado')
                ->where('empleado.no_equipo', $equipo_trabajo->no_equipo)
                ->where('estado_tarea.nombre', 'FINALIZADA')
                ->count(),
                
            'empleados_activos' => $equipo_trabajo->empleados()->count()
        ];
    
        return view('equipos.rendimiento', compact('equipo_trabajo', 'stats'));
    }

    public function proyectosActivos(EquipoTrabajo $equipo_trabajo)
    {
        $proyectos = $equipo_trabajo->proyectos()
            ->whereHas('estado', function($query) {
                $query->whereIn('nombre', ['CREADO', 'EN PROCESO']);
            })
            ->with(['cliente', 'estado'])
            ->get();

        return view('equipos.proyectos-activos', compact('equipo_trabajo', 'proyectos'));
    }
}