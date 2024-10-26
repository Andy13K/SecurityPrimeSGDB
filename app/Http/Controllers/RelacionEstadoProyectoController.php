<?php

namespace App\Http\Controllers;

use App\Models\RelacionEstadoProyecto;
use App\Models\Proyecto;
use App\Models\EstadoProyecto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RelacionEstadoProyectoController extends Controller
{
    public function index()
    {
        $relaciones = RelacionEstadoProyecto::with(['proyecto', 'estado'])
            ->orderBy('proyecto_no_proyecto')
            ->get();

        return view('relaciones-estado-proyecto.index', compact('relaciones'));
    }

    public function create()
    {
        $proyectos = Proyecto::all();
        $estados = EstadoProyecto::all();
        return view('relaciones-estado-proyecto.create', compact('proyectos', 'estados'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'estado_proyecto_no_estado' => 'required|exists:estado_proyecto,no_estado',
            'proyecto_no_proyecto' => 'required|exists:proyecto,no_proyecto'
        ]);

        try {
            DB::beginTransaction();

            // Verificar si ya existe una relación para este proyecto
            $existingRelation = RelacionEstadoProyecto::where('proyecto_no_proyecto', $request->proyecto_no_proyecto)->first();
            
            if ($existingRelation) {
                // Actualizar el estado existente
                $existingRelation->estado_proyecto_no_estado = $request->estado_proyecto_no_estado;
                $existingRelation->save();
            } else {
                // Crear nueva relación
                RelacionEstadoProyecto::create($request->all());
            }

            DB::commit();
            return redirect()->route('relacion_estado_proyecto.index')
                           ->with('success', 'Estado del proyecto actualizado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al actualizar el estado del proyecto: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $relacion = RelacionEstadoProyecto::findOrFail($id);
        $proyectos = Proyecto::all();
        $estados = EstadoProyecto::all();
        return view('relaciones-estado-proyecto.edit', compact('relacion', 'proyectos', 'estados'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'estado_proyecto_no_estado' => 'required|exists:estado_proyecto,no_estado',
            'proyecto_no_proyecto' => 'required|exists:proyecto,no_proyecto'
        ]);

        try {
            $relacion = RelacionEstadoProyecto::findOrFail($id);
            $relacion->update($request->all());

            return redirect()->route('relacion_estado_proyecto.index')
                           ->with('success', 'Relación actualizada exitosamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al actualizar la relación: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $relacion = RelacionEstadoProyecto::findOrFail($id);
            $relacion->delete();

            return redirect()->route('relacion_estado_proyecto.index')
                           ->with('success', 'Relación eliminada exitosamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar la relación: ' . $e->getMessage());
        }
    }

    // Métodos adicionales

    public function historialProyecto(Proyecto $proyecto)
    {
        $historial = RelacionEstadoProyecto::where('proyecto_no_proyecto', $proyecto->no_proyecto)
            ->with('estado')
            ->orderBy('created_at')
            ->get();

        return view('relaciones-estado-proyecto.historial', compact('proyecto', 'historial'));
    }

    public function cambiarEstado(Request $request, Proyecto $proyecto)
    {
        $request->validate([
            'estado_proyecto_no_estado' => 'required|exists:estado_proyecto,no_estado'
        ]);

        try {
            DB::beginTransaction();

            RelacionEstadoProyecto::where('proyecto_no_proyecto', $proyecto->no_proyecto)
                ->update(['estado_proyecto_no_estado' => $request->estado_proyecto_no_estado]);

            DB::commit();
            return redirect()->back()->with('success', 'Estado del proyecto actualizado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al actualizar el estado del proyecto.');
        }
    }
}