<?php

namespace App\Http\Controllers;

use App\Models\RecursoProyecto;
use App\Models\Proyecto;
use App\Models\Recurso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RecursoProyectoController extends Controller
{
    public function index()
    {
        $asignaciones = RecursoProyecto::with(['proyecto', 'recurso'])
            ->orderBy('fecha', 'desc')
            ->get();

        return view('recursos-proyecto.index', compact('asignaciones'));
    }

    public function create()
    {
        $proyectos = Proyecto::whereHas('estado', function($query) {
            $query->whereIn('nombre', ['CREADO', 'EN PROCESO']);
        })->get();
        
        $recursos = Recurso::orderBy('nombre')->get();
        
        return view('recursos-proyecto.create', compact('proyectos', 'recursos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'proyecto_no_proyecto' => 'required|exists:proyecto,no_proyecto',
            'recursos' => 'required|array',
            'recursos.*.recurso_no_recurso' => 'required|exists:recurso,no_recurso',
            'recursos.*.cantidad_asignada' => 'required|integer|min:1'
        ]);

        try {
            DB::beginTransaction();

            foreach ($request->recursos as $recurso) {
                // Verificar si ya existe una asignación
                $existingAssignment = RecursoProyecto::where([
                    'proyecto_no_proyecto' => $request->proyecto_no_proyecto,
                    'recurso_no_recurso' => $recurso['recurso_no_recurso']
                ])->first();

                if ($existingAssignment) {
                    // Actualizar cantidad existente
                    $existingAssignment->cantidad_asignada += $recurso['cantidad_asignada'];
                    $existingAssignment->fecha = now();
                    $existingAssignment->save();
                } else {
                    // Crear nueva asignación
                    RecursoProyecto::create([
                        'proyecto_no_proyecto' => $request->proyecto_no_proyecto,
                        'recurso_no_recurso' => $recurso['recurso_no_recurso'],
                        'cantidad_asignada' => $recurso['cantidad_asignada'],
                        'fecha' => now()
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('recurso_proyecto.index')
                           ->with('success', 'Recursos asignados exitosamente al proyecto.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al asignar recursos: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $asignacion = RecursoProyecto::with(['proyecto', 'recurso'])->findOrFail($id);
        return view('recursos-proyecto.show', compact('asignacion'));
    }

    public function edit($id)
    {
        $asignacion = RecursoProyecto::with(['proyecto', 'recurso'])->findOrFail($id);
        return view('recursos-proyecto.edit', compact('asignacion'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'cantidad_asignada' => 'required|integer|min:1'
        ]);

        try {
            DB::beginTransaction();

            $asignacion = RecursoProyecto::findOrFail($id);
            $asignacion->update([
                'cantidad_asignada' => $request->cantidad_asignada,
                'fecha' => now()
            ]);

            DB::commit();
            return redirect()->route('recurso_proyecto.index')
                           ->with('success', 'Asignación actualizada exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al actualizar asignación: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $asignacion = RecursoProyecto::findOrFail($id);
            $asignacion->delete();

            DB::commit();
            return redirect()->route('recurso_proyecto.index')
                           ->with('success', 'Asignación eliminada exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al eliminar asignación: ' . $e->getMessage());
        }
    }

    // Métodos adicionales

    public function asignacionesPorProyecto(Proyecto $proyecto)
    {
        $asignaciones = $proyecto->recursos()
            ->withPivot('cantidad_asignada', 'fecha')
            ->orderBy('fecha', 'desc')
            ->get();

        $totales = [
            'costo_total' => $asignaciones->sum(function($recurso) {
                return $recurso->costo * $recurso->pivot->cantidad_asignada;
            }),
            'precio_total' => $asignaciones->sum(function($recurso) {
                return $recurso->precio * $recurso->pivot->cantidad_asignada;
            })
        ];

        return view('recursos-proyecto.por-proyecto', compact('proyecto', 'asignaciones', 'totales'));
    }

    public function asignacionesPorRecurso(Recurso $recurso)
    {
        $asignaciones = $recurso->proyectos()
            ->withPivot('cantidad_asignada', 'fecha')
            ->orderBy('fecha', 'desc')
            ->get();

        $totales = [
            'cantidad_total' => $asignaciones->sum('pivot.cantidad_asignada'),
            'costo_total' => $asignaciones->sum(function($proyecto) use ($recurso) {
                return $recurso->costo * $proyecto->pivot->cantidad_asignada;
            })
        ];

        return view('recursos-proyecto.por-recurso', compact('recurso', 'asignaciones', 'totales'));
    }

    public function actualizarCantidades(Request $request, Proyecto $proyecto)
    {
        $request->validate([
            'recursos' => 'required|array',
            'recursos.*.recurso_no_recurso' => 'required|exists:recurso,no_recurso',
            'recursos.*.cantidad_asignada' => 'required|integer|min:0'
        ]);

        try {
            DB::beginTransaction();

            foreach ($request->recursos as $recurso) {
                if ($recurso['cantidad_asignada'] > 0) {
                    RecursoProyecto::updateOrCreate(
                        [
                            'proyecto_no_proyecto' => $proyecto->no_proyecto,
                            'recurso_no_recurso' => $recurso['recurso_no_recurso']
                        ],
                        [
                            'cantidad_asignada' => $recurso['cantidad_asignada'],
                            'fecha' => now()
                        ]
                    );
                } else {
                    // Si la cantidad es 0, eliminar la asignación
                    RecursoProyecto::where([
                        'proyecto_no_proyecto' => $proyecto->no_proyecto,
                        'recurso_no_recurso' => $recurso['recurso_no_recurso']
                    ])->delete();
                }
            }

            DB::commit();
            return redirect()->back()
                           ->with('success', 'Cantidades actualizadas exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al actualizar cantidades: ' . $e->getMessage());
        }
    }

    public function resumenRecursos(Proyecto $proyecto)
    {
        $resumen = [
            'total_items' => $proyecto->recursos()->count(),
            'costo_total' => $proyecto->recursos()->sum(DB::raw('costo * cantidad_asignada')),
            'precio_total' => $proyecto->recursos()->sum(DB::raw('precio * cantidad_asignada')),
            'ultimo_update' => $proyecto->recursos()->max('fecha')
        ];

        $recursos_por_categoria = $proyecto->recursos()
            ->withPivot('cantidad_asignada')
            ->get()
            ->groupBy(function($recurso) {
                // Agrupar por categoría basada en el nombre
                if (str_contains($recurso->nombre, 'Cámara')) return 'Cámaras';
                if (str_contains($recurso->nombre, 'NVR') || str_contains($recurso->nombre, 'DVR')) return 'Grabadores';
                if (str_contains($recurso->nombre, 'Disco')) return 'Almacenamiento';
                if (str_contains($recurso->nombre, 'Switch') || str_contains($recurso->nombre, 'Cable')) return 'Conectividad';
                return 'Otros';
            });

        return view('recursos-proyecto.resumen', compact('proyecto', 'resumen', 'recursos_por_categoria'));
    }
}