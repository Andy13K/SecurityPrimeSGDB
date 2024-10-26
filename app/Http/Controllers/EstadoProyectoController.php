<?php

namespace App\Http\Controllers;

use App\Models\EstadoProyecto;
use Illuminate\Http\Request;

class EstadoProyectoController extends Controller
{
    public function index()
    {
        $estados = EstadoProyecto::withCount('proyectos')->get();
        return view('estados-proyecto.index', compact('estados'));
    }

    public function create()
    {
        return view('estados-proyecto.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:64|unique:estado_proyecto,nombre'
        ]);

        try {
            EstadoProyecto::create($request->all());
            return redirect()->route('estado_proyecto.index')
                           ->with('success', 'Estado de proyecto creado exitosamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al crear el estado de proyecto: ' . $e->getMessage());
        }
    }

    public function edit(EstadoProyecto $estado_proyecto)
    {
        return view('estados-proyecto.edit', compact('estado_proyecto'));
    }

    public function update(Request $request, EstadoProyecto $estado_proyecto)
    {
        $request->validate([
            'nombre' => 'required|string|max:64|unique:estado_proyecto,nombre,' . 
                       $estado_proyecto->no_estado . ',no_estado'
        ]);

        try {
            $estado_proyecto->update($request->all());
            return redirect()->route('estado_proyecto.index')
                           ->with('success', 'Estado de proyecto actualizado exitosamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al actualizar el estado de proyecto: ' . $e->getMessage());
        }
    }

    public function destroy(EstadoProyecto $estado_proyecto)
    {
        try {
            if ($estado_proyecto->proyectos()->exists()) {
                return back()->with('error', 'No se puede eliminar el estado porque está siendo usado en proyectos.');
            }

            $estado_proyecto->delete();
            return redirect()->route('estado_proyecto.index')
                           ->with('success', 'Estado de proyecto eliminado exitosamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar el estado de proyecto: ' . $e->getMessage());
        }
    }
}