<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use App\Models\Cliente;
use App\Models\Tarea;
use App\Models\Empleado;
use App\Models\EquipoTrabajo;
use App\Models\Recurso;
use App\Models\EstadoProyecto;
use App\Models\EstadoTarea;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        try {
            // Obtener estadísticas generales
            $stats = [
                'proyectos_activos' => Proyecto::whereHas('estado', function($query) {
                    $query->where('estado_proyecto.nombre', 'EN PROCESO');
                })->count(),
                
                'total_clientes' => Cliente::count(),
                
                'tareas_pendientes' => Tarea::whereHas('estado', function($query) {
                    $query->whereIn('estado_tarea.nombre', ['CREADA', 'ASIGNADA']);
                })->count(),
                
                'total_empleados' => Empleado::count(),
                
                'equipos_activos' => EquipoTrabajo::count(),
                
                'recursos_disponibles' => Recurso::count()
            ];

            // Obtener proyectos recientes con sus relaciones
            $proyectosRecientes = Proyecto::with(['cliente', 'estado'])
                ->orderBy('fecha_inicio', 'desc')
                ->take(5)
                ->get();

            // Obtener datos para el gráfico de estados de proyectos
            $estadisticasProyectos = DB::table('proyecto')
                ->join('relacion_estado_proyecto', 'proyecto.no_proyecto', '=', 'relacion_estado_proyecto.proyecto_no_proyecto')
                ->join('estado_proyecto', 'relacion_estado_proyecto.estado_proyecto_no_estado', '=', 'estado_proyecto.no_estado')
                ->select('estado_proyecto.nombre', DB::raw('count(*) as total'))
                ->groupBy('estado_proyecto.nombre')
                ->pluck('total', 'nombre')
                ->toArray();

            // Obtener tareas pendientes prioritarias
            $tareasPendientes = Tarea::with(['empleado', 'estado'])
                ->whereHas('estado', function($query) {
                    $query->whereIn('estado_tarea.nombre', ['CREADA', 'ASIGNADA']);
                })
                ->orderBy('fecha_fin')
                ->take(4)
                ->get()
                ->map(function ($tarea) {
                    $diasRestantes = now()->diffInDays($tarea->fecha_fin, false);
                    $estado = $diasRestantes < 0 ? 'Atrasado' : 
                            ($diasRestantes == 0 ? 'Hoy' : 
                            ($diasRestantes == 1 ? 'Mañana' : 
                            'En ' . $diasRestantes . ' días'));
                    
                    return [
                        'descripcion' => $tarea->descripcion,
                        'empleado' => $tarea->empleado->nombre,
                        'estado' => $estado,
                        'clase' => $diasRestantes < 0 ? 'danger' : 
                                ($diasRestantes == 0 ? 'warning' : 
                                ($diasRestantes == 1 ? 'info' : 'primary'))
                    ];
                });

            // Obtener colores para los estados
            $estadosColores = [
                'CREADO' => 'warning',
                'EN PROCESO' => 'primary',
                'FINALIZADO' => 'success'
            ];

            return view('home', compact(
                'stats',
                'proyectosRecientes',
                'estadisticasProyectos',
                'tareasPendientes',
                'estadosColores'
            ));

        } catch (\Exception $e) {
            // Log del error
            \Log::error('Error en HomeController: ' . $e->getMessage());
            
            // Retornar vista con mensaje de error
            return view('home')->with('error', 'Hubo un problema al cargar los datos del dashboard.');
        }
    }

    /**
     * Helpers para el formato de los datos
     */
    private function getBadgeClass($estado)
    {
        return match (strtoupper($estado)) {
            'CREADO' => 'warning',
            'EN PROCESO' => 'primary',
            'FINALIZADO' => 'success',
            'CREADA' => 'info',
            'ASIGNADA' => 'primary',
            default => 'secondary',
        };
    }
}