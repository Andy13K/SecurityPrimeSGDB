<?php

namespace App\Http\Controllers;

use App\Models\Cargo;
use Illuminate\Http\Request;

class CargoController extends Controller
{
    public function index()
    {
        $cargos = Cargo::withCount('empleados')->get();
        return view('cargos.index', compact('cargos'));
    }

    public function create()
    {
        return view('cargos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:64|unique:cargo,nombre'
        ]);

        try {
            Cargo::create($request->all());
            return redirect()->route('cargo.index')
                           ->with('success', 'Cargo creado exitosamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al crear el cargo: ' . $e->getMessage());
        }
    }

    public function edit(Cargo $cargo)
    {
        return view('cargos.edit', compact('cargo'));
    }

    public function update(Request $request, Cargo $cargo)
    {
        $request->validate([
            'nombre' => 'required|string|max:64|unique:cargo,nombre,' . $cargo->no_cargo . ',no_cargo'
        ]);

        try {
            $cargo->update($request->all());
            return redirect()->route('cargo.index')
                           ->with('success', 'Cargo actualizado exitosamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al actualizar el cargo: ' . $e->getMessage());
        }
    }

    public function destroy(Cargo $cargo)
    {
        try {
            if ($cargo->empleados()->exists()) {
                return back()->with('error', 'No se puede eliminar el cargo porque tiene empleados asignados.');
            }

            $cargo->delete();
            return redirect()->route('cargo.index')
                           ->with('success', 'Cargo eliminado exitosamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar el cargo: ' . $e->getMessage());
        }
    }
}