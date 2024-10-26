<?php

namespace App\Http\Controllers;

use App\Models\Especializacion;
use Illuminate\Http\Request;

class EspecializacionController extends Controller
{
    public function index()
    {
        $especializaciones = Especializacion::withCount('empleados')->get();
        return view('especializaciones.index', compact('especializaciones'));
    }

    public function create()
    {
        return view('especializaciones.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:64|unique:especializacion,nombre'
        ]);

        try {
            Especializacion::create($request->all());
            return redirect()->route('especializacion.index')
                           ->with('success', 'Especialización creada exitosamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al crear la especialización: ' . $e->getMessage());
        }
    }

    public function edit(Especializacion $especializacion)
    {
        return view('especializaciones.edit', compact('especializacion'));
    }

    public function update(Request $request, Especializacion $especializacion)
    {
        $request->validate([
            'nombre' => 'required|string|max:64|unique:especializacion,nombre,' . 
                       $especializacion->no_especializacion . ',no_especializacion'
        ]);

        try {
            $especializacion->update($request->all());
            return redirect()->route('especializacion.index')
                           ->with('success', 'Especialización actualizada exitosamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al actualizar la especialización: ' . $e->getMessage());
        }
    }

    public function destroy(Especializacion $especializacion)
    {
        try {
            if ($especializacion->empleados()->exists()) {
                return back()->with('error', 'No se puede eliminar la especialización porque tiene empleados asignados.');
            }

            $especializacion->delete();
            return redirect()->route('especializacion.index')
                           ->with('success', 'Especialización eliminada exitosamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar la especialización: ' . $e->getMessage());
        }
    }
}