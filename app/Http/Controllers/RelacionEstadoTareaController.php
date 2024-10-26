<?php

namespace App\Http\Controllers;

use App\Models\RelacionEstadoTarea;
use App\Models\Tarea;
use App\Models\Proyecto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RelacionEstadoTareaController extends Controller
{
    public function index()
    {
        $relaciones = RelacionEstadoTarea::with(['tarea', 'proyecto'])
            ->orderBy('proyecto_no_proyecto')
            ->get();

        return view('relaciones-estado-tarea.index', compact('relaciones'));
    }

    public function create()
    {
        $tareas = Tarea::all();
        $proyectos = Proyecto::all();
        return view('relaciones-estado-tarea.create', compact('tareas', 'proyectos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tarea_no_tarea' => 'required|exists:tarea,no_tarea',
            'proyecto_no_proyecto' => 'required|exists:proyecto,no_proyecto'
        ]);

        try {
            DB::beginTransaction();

            // Verificar que la tarea no esté ya asignada a otro proyecto
            $existingRelation = RelacionEstadoTarea::where('tarea_no_tarea', $request->tarea_no_tarea)->first();
            
            if ($existingRelation) {
                return back()->with('error', 'Esta tarea ya está asignada a un proyecto.');
            }

            RelacionEstadoTarea::create($request->all());

            DB::commit();
            return redirect()->route('relacion_estado_tarea.index')
                           ->with('success', 'Tarea asignada al proyecto exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al asignar la tarea al proyecto: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $relacion = RelacionEstadoTarea::findOrFail($id);
        $tareas = Tarea::all();
        $proyectos = Proyecto::all();
        return view('relaciones-estado-tarea.edit', compact('relacion', 'tareas', 'proyectos'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tarea_no_tarea' => 'required|exists:tarea,no_tarea',
            'proyecto_no_proyecto' => 'required|exists:proyecto,no_proyecto'
        ]);

        try {
            $relacion = RelacionEstadoTarea::findOrFail($id);
            $relacion->update($request->all());

            return redirect()->route('relacion_estado_tarea.index')
                           ->with('success', 'Relación actualizada exitosamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al actualizar la relación: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $relacion = RelacionEstadoTarea::findOrFail($id);
            $relacion->delete();

            return redirect()->route('relacion_estado_tarea.index')
                           ->with('success', 'Relación eliminada exitosamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar la relación: ' . $e->getMessage());
        }
    }

    // Métodos adicionales

    public function tareasProyecto(Proyecto $proyecto)
    {
        $tareas = RelacionEstadoTarea::where('proyecto_no_proyecto', $proyecto->no_proyecto)
            ->with(['tarea.empleado', 'tarea.estado'])
            ->get();

        return view('relaciones-estado-tarea.tareas-proyecto', compact('proyecto', 'tareas'));
    }

    public function reasignarTarea(Request $request, Tarea $tarea)
    {
        $request->validate([
            'proyecto_no_proyecto' => 'required|exists:proyecto,no_proyecto'
        ]);

        try {
            DB::beginTransaction();

            RelacionEstadoTarea::where('tarea_no_tarea', $tarea->no_tarea)
                ->update(['proyecto_no_proyecto' => $request->proyecto_no_proyecto]);

            DB::commit();
            return redirect()->back()->with('success', 'Tarea reasignada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al reasignar la tarea.');
        }
    }
}