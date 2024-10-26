<?php

namespace App\Http\Controllers;

use App\Models\EstadoTarea;
use Illuminate\Http\Request;

class EstadoTareaController extends Controller
{
    public function index()
    {
        $estados = EstadoTarea::withCount('tareas')->get();
        return view('estados-tarea.index', compact('estados'));
    }

    public function create()
    {
        return view('estados-tarea.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:64|unique:estado_tarea,nombre'
        ]);

        try {
            EstadoTarea::create($request->all());
            return redirect()->route('estado_tarea.index')
                           ->with('success', 'Estado de tarea creado exitosamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al crear el estado de tarea: ' . $e->getMessage());
        }
    }

    public function edit(EstadoTarea $estado_tarea)
    {
        return view('estados-tarea.edit', compact('estado_tarea'));
    }

    public function update(Request $request, EstadoTarea $estado_tarea)
    {
        $request->validate([
            'nombre' => 'required|string|max:64|unique:estado_tarea,nombre,' . 
                       $estado_tarea->no_estado . ',no_estado'
        ]);

        try {
            $estado_tarea->update($request->all());
            return redirect()->route('estado_tarea.index')
                           ->with('success', 'Estado de tarea actualizado exitosamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al actualizar el estado de tarea: ' . $e->getMessage());
        }
    }

    public function destroy(EstadoTarea $estado_tarea)
    {
        try {
            if ($estado_tarea->tareas()->exists()) {
                return back()->with('error', 'No se puede eliminar el estado porque está siendo usado en tareas.');
            }

            $estado_tarea->delete();
            return redirect()->route('estado_tarea.index')
                           ->with('success', 'Estado de tarea eliminado exitosamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar el estado de tarea: ' . $e->getMessage());
        }
    }
}