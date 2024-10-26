<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use App\Models\Cliente;
use App\Models\Tarea;
use App\Models\Empleado;
use App\Models\EquipoTrabajo;
use App\Models\Recurso;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Obtener estadísticas generales
        $stats = [
            'proyectos_activos' => Proyecto::whereHas('status', function($query) {
                $query->where('nombre', 'EN PROCESO');
            })->count(),
            
            'total_clientes' => Cliente::count(),
            
            'tareas_pendientes' => Tarea::whereHas('estado', function($query) {
                $query->whereIn('nombre', ['CREADA', 'ASIGNADA']);
            })->count(),
            
            'total_empleados' => Empleado::count(),
            
            'equipos_activos' => EquipoTrabajo::count(),
            
            'recursos_disponibles' => Recurso::count()
        ];

        // Obtener proyectos recientes
        $proyectosRecientes = Proyecto::with(['cliente', 'status'])
            ->orderBy('fecha_inicio', 'desc')
            ->take(5)
            ->get()
            ->map(function ($proyecto) {
                return [
                    'nombre' => $proyecto->nombre,
                    'cliente' => $proyecto->cliente->nombre,
                    'fecha_inicio' => $proyecto->fecha_inicio->format('Y-m-d'),
                    'estado' => $proyecto->status->first()->nombre,
                ];
            });

        // Obtener datos para el gráfico de proyectos
        $estadisticasProyectos = Proyecto::select('estado_proyecto.nombre as estado', DB::raw('count(*) as total'))
            ->join('relacion_estado_proyecto', 'proyecto.no_proyecto', '=', 'relacion_estado_proyecto.proyecto_no_proyecto')
            ->join('estado_proyecto', 'relacion_estado_proyecto.estado_proyecto_no_estado', '=', 'estado_proyecto.no_estado')
            ->groupBy('estado_proyecto.nombre')
            ->get()
            ->pluck('total', 'estado')
            ->toArray();

        // Obtener tareas pendientes prioritarias
        $tareasPrioritarias = Tarea::with(['estado', 'empleado'])
            ->whereHas('estado', function($query) {
                $query->whereIn('nombre', ['CREADA', 'ASIGNADA']);
            })
            ->orderBy('fecha_fin', 'asc')
            ->take(4)
            ->get()
            ->map(function ($tarea) {
                return [
                    'descripcion' => $tarea->descripcion,
                    'fecha_fin' => $tarea->fecha_fin->diffForHumans(),
                    'estado' => $tarea->estado->nombre,
                    'empleado' => $tarea->empleado->nombre,
                ];
            });

        return view('dashboard', compact(
            'stats',
            'proyectosRecientes',
            'estadisticasProyectos',
            'tareasPrioritarias'
        ));
    }
}