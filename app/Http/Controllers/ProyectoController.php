<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use App\Models\Cliente;
use App\Models\EquipoTrabajo;
use App\Models\TipoEntorno;
use App\Models\EstadoProyecto;
use App\Models\Recurso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProyectoController extends Controller
{
    public function index()
    {
        $proyectos = Proyecto::with(['cliente', 'equipo', 'estado', 'tipoEntorno'])
            ->orderBy('fecha_inicio', 'desc')
            ->get();

        return view('proyectos.index', compact('proyectos'));
    }

    public function create()
    {
        $clientes = Cliente::all();
        $equipos = EquipoTrabajo::all();
        $tiposEntorno = TipoEntorno::all();
        $recursos = Recurso::all();

        return view('proyectos.create', compact('clientes', 'equipos', 'tiposEntorno', 'recursos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'required|string|max:64',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'no_tipo_entorno' => 'required|exists:tipo_entorno,no_entorno',
            'no_equipo' => 'required|exists:equipo_trabajo,no_equipo',
            'no_cliente' => 'required|exists:cliente,no_cliente',
            'mano_obra' => 'required|numeric|min:0',
            'recursos' => 'array',
            'recursos.*.recurso_id' => 'exists:recurso,no_recurso',
            'recursos.*.cantidad' => 'numeric|min:1'
        ]);

        try {
            DB::beginTransaction();

            // Crear el proyecto
            $proyecto = Proyecto::create($request->except('recursos'));

            // Asignar estado inicial (CREADO)
            $estadoInicial = EstadoProyecto::where('nombre', 'CREADO')->first();
            $proyecto->estado()->attach($estadoInicial->no_estado);

            // Asignar recursos si existen
            if ($request->has('recursos')) {
                foreach ($request->recursos as $recurso) {
                    if (isset($recurso['recurso_id']) && isset($recurso['cantidad'])) {
                        DB::table('recurso_proyecto')->insert([
                            'proyecto_no_proyecto' => $proyecto->no_proyecto,
                            'recurso_no_recurso' => $recurso['recurso_id'],
                            'cantidad_asignada' => $recurso['cantidad'],
                            'fecha' => DB::raw('SYSDATE')
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('proyecto.index')
                           ->with('success', 'Proyecto creado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                        ->with('error', 'Error al crear el proyecto: ' . $e->getMessage());
        }
    }

    public function show(Proyecto $proyecto)
    {
        $proyecto->load([
            'cliente',
            'equipo',
            'estado',
            'tipoEntorno',
            'recursos',
            'tareas' => function($query) {
                $query->with(['empleado', 'estado']);
            }
        ]);

        return view('proyectos.show', compact('proyecto'));
    }

    public function edit(Proyecto $proyecto)
    {
        $clientes = Cliente::all();
        $equipos = EquipoTrabajo::all();
        $tiposEntorno = TipoEntorno::all();
        $recursos = Recurso::all();
        $estados = EstadoProyecto::all();

        $proyecto->load(['recursos', 'estado']);

        return view('proyectos.edit', compact(
            'proyecto', 
            'clientes', 
            'equipos', 
            'tiposEntorno', 
            'recursos',
            'estados'
        ));
    }

    public function update(Request $request, Proyecto $proyecto)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'required|string|max:64',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'no_tipo_entorno' => 'required|exists:tipo_entorno,no_entorno',
            'no_equipo' => 'required|exists:equipo_trabajo,no_equipo',
            'no_cliente' => 'required|exists:cliente,no_cliente',
            'mano_obra' => 'required|numeric|min:0',
            'recursos' => 'array',
            'estado_id' => 'required|exists:estado_proyecto,no_estado'
        ]);

        try {
            DB::beginTransaction();

            // Actualizar proyecto
            $proyecto->update($request->except('recursos', 'estado_id'));

            // Actualizar estado
            $proyecto->estado()->sync([$request->estado_id]);

            // Actualizar recursos
            if ($request->has('recursos')) {
                // Primero eliminamos los recursos existentes
                DB::table('recurso_proyecto')
                    ->where('proyecto_no_proyecto', $proyecto->no_proyecto)
                    ->delete();

                // Luego insertamos los nuevos recursos
                foreach ($request->recursos as $recurso) {
                    if (isset($recurso['recurso_id']) && isset($recurso['cantidad']) && $recurso['cantidad'] > 0) {
                        DB::table('recurso_proyecto')->insert([
                            'proyecto_no_proyecto' => $proyecto->no_proyecto,
                            'recurso_no_recurso' => $recurso['recurso_id'],
                            'cantidad_asignada' => $recurso['cantidad'],
                            'fecha' => DB::raw('SYSDATE')
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('proyecto.show', $proyecto)
                           ->with('success', 'Proyecto actualizado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                        ->with('error', 'Error al actualizar el proyecto: ' . $e->getMessage());
        }
    }

    public function destroy(Proyecto $proyecto)
    {
        try {
            DB::beginTransaction();

            // Eliminar relaciones
            $proyecto->estado()->detach();
            DB::table('recurso_proyecto')
                ->where('proyecto_no_proyecto', $proyecto->no_proyecto)
                ->delete();
            
            // Eliminar tareas asociadas
            $proyecto->tareas()->delete();
            
            // Eliminar el proyecto
            $proyecto->delete();

            DB::commit();
            return redirect()->route('proyecto.index')
                           ->with('success', 'Proyecto eliminado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al eliminar el proyecto: ' . $e->getMessage());
        }
    }

    public function cambiarEstado(Request $request, Proyecto $proyecto)
    {
        $request->validate([
            'estado_id' => 'required|exists:estado_proyecto,no_estado'
        ]);

        try {
            DB::beginTransaction();
            $proyecto->estado()->sync([$request->estado_id]);
            DB::commit();
            
            return redirect()->back()->with('success', 'Estado del proyecto actualizado.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al cambiar el estado del proyecto.');
        }
    }

    public function asignarRecursos(Request $request, Proyecto $proyecto)
    {
        $request->validate([
            'recursos' => 'required|array',
            'recursos.*.recurso_id' => 'required|exists:recurso,no_recurso',
            'recursos.*.cantidad' => 'required|numeric|min:1'
        ]);

        try {
            DB::beginTransaction();

            // Eliminar recursos existentes
            DB::table('recurso_proyecto')
                ->where('proyecto_no_proyecto', $proyecto->no_proyecto)
                ->delete();

            // Insertar nuevos recursos
            foreach ($request->recursos as $recurso) {
                if ($recurso['cantidad'] > 0) {
                    DB::table('recurso_proyecto')->insert([
                        'proyecto_no_proyecto' => $proyecto->no_proyecto,
                        'recurso_no_recurso' => $recurso['recurso_id'],
                        'cantidad_asignada' => $recurso['cantidad'],
                        'fecha' => DB::raw('SYSDATE')
                    ]);
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'Recursos actualizados correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al asignar recursos al proyecto.');
        }
    }

    public function dashboard(Proyecto $proyecto)
    {
        $proyecto->load(['tareas.empleado', 'recursos', 'cliente', 'equipo']);
        
        $stats = [
            'total_tareas' => $proyecto->tareas->count(),
            'tareas_completadas' => $proyecto->tareas->whereHas('estado', function($q) {
                $q->where('nombre', 'FINALIZADA');
            })->count(),
            'dias_restantes' => now()->diffInDays($proyecto->fecha_fin, false),
            'costo_total' => $proyecto->calcularCostoTotal(),
            'margen_esperado' => $proyecto->calcularMargen(),
            'porcentaje_completado' => $proyecto->calcularPorcentajeCompletado()
        ];

        return view('proyectos.dashboard', compact('proyecto', 'stats'));
    }
}