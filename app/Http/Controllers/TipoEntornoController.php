<?php

namespace App\Http\Controllers;

use App\Models\TipoEntorno;
use Illuminate\Http\Request;

class TipoEntornoController extends Controller
{
    public function index()
    {
        $tiposEntorno = TipoEntorno::withCount('proyectos')->get();
        return view('tipos-entorno.index', compact('tiposEntorno'));
    }

    public function create()
    {
        return view('tipos-entorno.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:64|unique:tipo_entorno,nombre',
            'descripcion' => 'required|string|max:256'
        ]);

        try {
            TipoEntorno::create($request->all());
            return redirect()->route('tipo_entorno.index')
                           ->with('success', 'Tipo de entorno creado exitosamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al crear el tipo de entorno: ' . $e->getMessage());
        }
    }

    public function edit(TipoEntorno $tipo_entorno)
    {
        return view('tipos-entorno.edit', compact('tipo_entorno'));
    }

    public function update(Request $request, TipoEntorno $tipo_entorno)
    {
        $request->validate([
            'nombre' => 'required|string|max:64|unique:tipo_entorno,nombre,' . 
                       $tipo_entorno->no_entorno . ',no_entorno',
            'descripcion' => 'required|string|max:256'
        ]);

        try {
            $tipo_entorno->update($request->all());
            return redirect()->route('tipo_entorno.index')
                           ->with('success', 'Tipo de entorno actualizado exitosamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al actualizar el tipo de entorno: ' . $e->getMessage());
        }
    }

    public function destroy(TipoEntorno $tipo_entorno)
    {
        try {
            if ($tipo_entorno->proyectos()->exists()) {
                return back()->with('error', 'No se puede eliminar el tipo de entorno porque está siendo usado en proyectos.');
            }

            $tipo_entorno->delete();
            return redirect()->route('tipo_entorno.index')
                           ->with('success', 'Tipo de entorno eliminado exitosamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar el tipo de entorno: ' . $e->getMessage());
        }
    }
}