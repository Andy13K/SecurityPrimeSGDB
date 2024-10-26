<?php

namespace App\Http\Controllers;

use App\Models\Recurso;
use App\Models\Proyecto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RecursoController extends Controller
{
    public function index()
    {
        $recursos = Recurso::withCount('proyectos')
            ->orderBy('nombre')
            ->get()
            ->groupBy(function($recurso) {
                // Agrupar por tipo basado en el nombre
                if (str_contains($recurso->nombre, 'Cámara')) return 'Cámaras';
                if (str_contains($recurso->nombre, 'NVR') || str_contains($recurso->nombre, 'DVR')) return 'Grabadores';
                if (str_contains($recurso->nombre, 'Disco')) return 'Almacenamiento';
                if (str_contains($recurso->nombre, 'Switch') || str_contains($recurso->nombre, 'Cable')) return 'Conectividad';
                return 'Otros';
            });

        return view('recursos.index', compact('recursos'));
    }

    public function create()
    {
        return view('recursos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:64|unique:recurso,nombre',
            'precio' => 'required|numeric|min:0',
            'costo' => 'required|numeric|min:0|lte:precio'
        ]);

        try {
            Recurso::create($request->all());
            return redirect()->route('recurso.index')
                           ->with('success', 'Recurso creado exitosamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al crear el recurso: ' . $e->getMessage());
        }
    }

    public function show(Recurso $recurso)
    {
        $recurso->load(['proyectos' => function($query) {
            $query->orderBy('fecha_inicio', 'desc');
        }]);

        $stats = [
            'total_asignado' => $recurso->proyectos->sum('pivot.cantidad_asignada'),
            'proyectos_activos' => $recurso->proyectos()
                ->whereHas('estado', function($q) {
                    $q->whereIn('nombre', ['CREADO', 'EN PROCESO']);
                })->count(),
            'margen' => $recurso->precio - $recurso->costo,
            'margen_porcentaje' => (($recurso->precio - $recurso->costo) / $recurso->precio) * 100
        ];

        return view('recursos.show', compact('recurso', 'stats'));
    }

    public function edit(Recurso $recurso)
    {
        return view('recursos.edit', compact('recurso'));
    }

    public function update(Request $request, Recurso $recurso)
    {
        $request->validate([
            'nombre' => 'required|string|max:64|unique:recurso,nombre,' . $recurso->no_recurso . ',no_recurso',
            'precio' => 'required|numeric|min:0',
            'costo' => 'required|numeric|min:0|lte:precio'
        ]);

        try {
            $recurso->update($request->all());
            return redirect()->route('recurso.show', $recurso)
                           ->with('success', 'Recurso actualizado exitosamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al actualizar el recurso: ' . $e->getMessage());
        }
    }

    public function destroy(Recurso $recurso)
    {
        try {
            // Verificar si está siendo usado en algún proyecto
            if ($recurso->proyectos()->exists()) {
                return back()->with('error', 'No se puede eliminar el recurso porque está siendo utilizado en proyectos.');
            }

            $recurso->delete();
            return redirect()->route('recurso.index')
                           ->with('success', 'Recurso eliminado exitosamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar el recurso: ' . $e->getMessage());
        }
    }

    // Métodos adicionales

    public function asignarAProyecto(Request $request, Recurso $recurso)
    {
        $request->validate([
            'proyecto_id' => 'required|exists:proyecto,no_proyecto',
            'cantidad' => 'required|integer|min:1'
        ]);

        try {
            DB::beginTransaction();

            $proyecto = Proyecto::find($request->proyecto_id);
            
            $proyecto->recursos()->attach($recurso->no_recurso, [
                'cantidad_asignada' => $request->cantidad,
                'fecha' => now()
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Recurso asignado exitosamente al proyecto.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al asignar el recurso al proyecto.');
        }
    }

    public function actualizarPrecio(Request $request, Recurso $recurso)
    {
        $request->validate([
            'precio' => 'required|numeric|min:0',
            'costo' => 'required|numeric|min:0|lte:precio'
        ]);

        try {
            $recurso->update($request->only(['precio', 'costo']));
            return redirect()->back()->with('success', 'Precios actualizados exitosamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al actualizar los precios.');
        }
    }

    public function inventario()
    {
        $recursos = Recurso::withCount(['proyectos as cantidad_asignada' => function($query) {
            $query->select(DB::raw('SUM(cantidad_asignada)'));
        }])->get();

        return view('recursos.inventario', compact('recursos'));
    }

    public function reporteUso(Recurso $recurso)
    {
        $uso_mensual = DB::table('recurso_proyecto')
            ->join('proyecto', 'proyecto.no_proyecto', '=', 'recurso_proyecto.proyecto_no_proyecto')
            ->select(
                DB::raw("TO_CHAR(fecha_inicio, 'YYYY-MM') as mes"),
                DB::raw('SUM(recurso_proyecto.cantidad_asignada) as total')
            )
            ->where('recurso_proyecto.recurso_no_recurso', '=', $recurso->no_recurso)
            ->groupBy(DB::raw("TO_CHAR(fecha_inicio, 'YYYY-MM')"))
            ->orderBy('mes', 'desc')
            ->get();
    
        return view('recursos.reporte-uso', compact('recurso', 'uso_mensual'));
    }
}