<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Cliente::withCount('proyectos')->get();
        return view('clientes.index', compact('clientes'));
    }

    public function create()
    {
        return view('clientes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:64',
            'direccion' => 'required|string|max:64',
            'correo' => 'required|email|max:64|unique:cliente,correo',
            'telefono' => 'required|digits:8|unique:cliente,telefono'
        ]);

        try {
            Cliente::create($request->all());
            return redirect()->route('cliente.index')
                           ->with('success', 'Cliente creado exitosamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al crear el cliente: ' . $e->getMessage());
        }
    }

    public function show(Cliente $cliente)
    {
        $cliente->load(['proyectos.estado', 'proyectos.equipo']);
        return view('clientes.show', compact('cliente'));
    }

    public function edit(Cliente $cliente)
    {
        return view('clientes.edit', compact('cliente'));
    }

    public function update(Request $request, Cliente $cliente)
    {
        $request->validate([
            'nombre' => 'required|string|max:64',
            'direccion' => 'required|string|max:64',
            'correo' => 'required|email|max:64|unique:cliente,correo,' . $cliente->no_cliente . ',no_cliente',
            'telefono' => 'required|digits:8|unique:cliente,telefono,' . $cliente->no_cliente . ',no_cliente'
        ]);

        try {
            $cliente->update($request->all());
            return redirect()->route('cliente.show', $cliente)
                           ->with('success', 'Cliente actualizado exitosamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al actualizar el cliente: ' . $e->getMessage());
        }
    }

    public function destroy(Cliente $cliente)
    {
        try {
            // Verificar si tiene proyectos
            if ($cliente->proyectos()->exists()) {
                return back()->with('error', 'No se puede eliminar el cliente porque tiene proyectos asociados.');
            }

            $cliente->delete();
            return redirect()->route('cliente.index')
                           ->with('success', 'Cliente eliminado exitosamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar el cliente: ' . $e->getMessage());
        }
    }

    // Métodos adicionales

    public function proyectos(Cliente $cliente)
    {
        $proyectos = $cliente->proyectos()
                            ->with(['estado', 'equipo'])
                            ->orderBy('fecha_inicio', 'desc')
                            ->get();

        return view('clientes.proyectos', compact('cliente', 'proyectos'));
    }

    public function dashboard(Cliente $cliente)
    {
        $stats = [
            'total_proyectos' => $cliente->proyectos()->count(),
            'proyectos_activos' => $cliente->proyectos()
                ->whereHas('estado', function($query) {
                    $query->where('nombre', 'EN PROCESO');
                })->count(),
            'inversion_total' => $cliente->proyectos()->sum('mano_obra'),
            'ultimo_proyecto' => $cliente->proyectos()
                ->orderBy('fecha_inicio', 'desc')
                ->first()
        ];

        return view('clientes.dashboard', compact('cliente', 'stats'));
    }
}