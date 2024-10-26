<?php

namespace App\Http\Controllers;

use App\Models\Tarea;
use App\Models\Proyecto;
use App\Models\Empleado;
use App\Models\EstadoTarea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TareaController extends Controller
{
    public function index()
    {
        $tareas = Tarea::with(['empleado.cargo', 'estado', 'proyecto'])
            ->orderBy('fecha_inicio', 'desc')
            ->get();
    
        $empleados = Empleado::with('cargo')
            ->orderBy('nombre')
            ->get();
    
        return view('tareas.index', compact('tareas', 'empleados'));
    }

    public function create()
    {
        $proyectos = Proyecto::all();
        $empleados = Empleado::all();
        $estados = EstadoTarea::all();

        return view('tareas.create', compact('proyectos', 'empleados', 'estados'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'descripcion' => 'required|string|max:256',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'empleado_no' => 'required|exists:empleado,no_empleado',
            'proyecto_no_proyecto' => 'required|exists:proyecto,no_proyecto'
        ]);
    
        try {
            DB::beginTransaction();
    
            // Obtener el estado inicial (CREADA)
            $estadoInicial = EstadoTarea::where('nombre', 'CREADA')->first();
            if (!$estadoInicial) {
                throw new \Exception('No se encontró el estado inicial para la tarea.');
            }
    
            // Crear la tarea con todos los campos necesarios
            $tarea = Tarea::create([
                'descripcion' => $request->descripcion,
                'fecha_inicio' => $request->fecha_inicio,
                'fecha_fin' => $request->fecha_fin,
                'empleado_no' => $request->empleado_no,
                'no_proyecto' => $request->proyecto_no_proyecto, // Asegúrate que este campo coincida con tu base de datos
                'estado_tarea' => $estadoInicial->no_estado,
                'comprobacion' => 'Pendiente verificación'
            ]);
    
            DB::commit();
            return redirect()->route('tarea.index')
                           ->with('success', 'Tarea creada exitosamente.');
    
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Error al crear la tarea: ' . $e->getMessage());
        }
    }

    public function show(Tarea $tarea)
    {
        $tarea->load(['empleado', 'estado', 'proyecto']);
        return view('tareas.show', compact('tarea'));
    }

    public function edit(Tarea $tarea)
    {
        $proyectos = Proyecto::all();
        $empleados = Empleado::all();
        $estados = EstadoTarea::all();

        return view('tareas.edit', compact('tarea', 'proyectos', 'empleados', 'estados'));
    }

    public function update(Request $request, Tarea $tarea)
    {
        $request->validate([
            'descripcion' => 'required|string|max:256',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'empleado_no' => 'required|exists:empleado,no_empleado',
            'estado_tarea' => 'required|exists:estado_tarea,no_estado',
            'comprobacion' => 'nullable|string|max:256',
            'evidencia' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048'
        ]);

        try {
            DB::beginTransaction();

            // Si se está finalizando la tarea, verificar que haya evidencia
            if ($request->estado_tarea == EstadoTarea::where('nombre', 'FINALIZADA')->first()->no_estado) {
                if (!$request->hasFile('evidencia') && !$tarea->comprobacion) {
                    return back()->with('error', 'Debe proporcionar evidencia para finalizar la tarea.');
                }
            }

            // Manejar la evidencia si se proporcionó
            if ($request->hasFile('evidencia')) {
                // Eliminar evidencia anterior si existe
                if ($tarea->comprobacion && Storage::exists($tarea->comprobacion)) {
                    Storage::delete($tarea->comprobacion);
                }

                // Guardar nueva evidencia
                $path = $request->file('evidencia')->store('evidencias');
                $request->merge(['comprobacion' => $path]);
            }

            $tarea->update($request->all());

            DB::commit();
            return redirect()->route('tarea.show', $tarea)
                           ->with('success', 'Tarea actualizada exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al actualizar la tarea: ' . $e->getMessage());
        }
    }

    public function destroy(Tarea $tarea)
    {
        try {
            DB::beginTransaction();

            // Eliminar evidencia si existe
            if ($tarea->comprobacion && Storage::exists($tarea->comprobacion)) {
                Storage::delete($tarea->comprobacion);
            }

            // Eliminar relaciones
            DB::table('relacion_estado_tarea')
              ->where('tarea_no_tarea', $tarea->no_tarea)
              ->delete();

            // Eliminar la tarea
            $tarea->delete();

            DB::commit();
            return redirect()->route('tarea.index')
                           ->with('success', 'Tarea eliminada exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al eliminar la tarea: ' . $e->getMessage());
        }
    }

    // Métodos adicionales

    public function asignar(Request $request, Tarea $tarea)
    {
        $request->validate([
            'empleado_no' => 'required|exists:empleado,no_empleado'
        ]);

        try {
            DB::beginTransaction();

            // Cambiar estado a ASIGNADA
            $estadoAsignada = EstadoTarea::where('nombre', 'ASIGNADA')->first();
            
            $tarea->update([
                'empleado_no' => $request->empleado_no,
                'estado_tarea' => $estadoAsignada->no_estado,
                'comprobacion' => 'En proceso'
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Tarea asignada correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al asignar la tarea.');
        }
    }

    public function finalizar(Request $request, Tarea $tarea)
    {
        $request->validate([
            'evidencia' => 'required|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'comprobacion' => 'required|string|max:256'
        ]);

        try {
            DB::beginTransaction();

            // Guardar evidencia
            $path = $request->file('evidencia')->store('evidencias');

            // Cambiar estado a FINALIZADA
            $estadoFinalizada = EstadoTarea::where('nombre', 'FINALIZADA')->first();
            
            $tarea->update([
                'estado_tarea' => $estadoFinalizada->no_estado,
                'comprobacion' => $path
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Tarea finalizada correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al finalizar la tarea.');
        }
    }

    public function misTareas()
    {
        $tareas = Tarea::with(['proyecto', 'estado'])
            ->where('empleado_no', auth()->user()->empleado->no_empleado)
            ->orderBy('fecha_fin')
            ->get()
            ->groupBy(function($tarea) {
                if ($tarea->estado->nombre == 'FINALIZADA') return 'completadas';
                if ($tarea->fecha_fin < now()) return 'atrasadas';
                if ($tarea->fecha_fin->diffInDays(now()) <= 2) return 'urgentes';
                return 'pendientes';
            });

        return view('tareas.mis-tareas', compact('tareas'));
    }

    public function verEvidencia(Tarea $tarea)
    {
        if (!$tarea->comprobacion || !Storage::exists($tarea->comprobacion)) {
            return back()->with('error', 'No hay evidencia disponible para esta tarea.');
        }

        return Storage::response($tarea->comprobacion);
    }
}