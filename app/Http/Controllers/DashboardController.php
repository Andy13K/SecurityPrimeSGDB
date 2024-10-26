<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use App\Models\Cliente;
use App\Models\Tarea;
use App\Models\Empleado;
use App\Models\EstadoProyecto;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $proyectosActivos = Proyecto::where('estado', 'activo')->count();
        $totalClientes = Cliente::count();
        $tareasPendientes = Tarea::where('estado', 'pendiente')->count();
        $totalEmpleados = Empleado::count();
        
        $proyectosRecientes = Proyecto::with(['cliente', 'estado'])
            ->orderBy('fecha_inicio', 'desc')
            ->take(5)
            ->get();
        
        $estadosProyecto = EstadoProyecto::withCount('proyectos')
            ->get()
            ->map(function ($estado) {
                $estado->color = $this->getColorForEstado($estado->nombre);
                return $estado;
            });
        
        $tareasPrioritarias = Tarea::where('estado', 'pendiente')
            ->orderBy('fecha_fin', 'asc')
            ->take(5)
            ->get()
            ->map(function ($tarea) {
                $tarea->prioridad_color = $this->getColorForPrioridad($tarea->prioridad);
                return $tarea;
            });

        return view('dashboard', compact(
            'proyectosActivos', 
            'totalClientes', 
            'tareasPendientes', 
            'totalEmpleados', 
            'proyectosRecientes', 
            'estadosProyecto', 
            'tareasPrioritarias'
        ));
    }

    private function getColorForEstado($estado)
    {
        $colores = [
            'activo' => 'primary',
            'completado' => 'success',
            'en espera' => 'warning',
            'cancelado' => 'danger'
        ];

        return $colores[$estado] ?? 'secondary';
    }

    private function getColorForPrioridad($prioridad)
    {
        $colores = [
            'alta' => 'danger',
            'media' => 'warning',
            'baja' => 'info'
        ];

        return $colores[$prioridad] ?? 'secondary';
    }
}