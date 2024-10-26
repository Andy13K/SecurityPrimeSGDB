@extends('layouts.app')

@section('title', 'Rendimiento del Empleado')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Rendimiento del Empleado</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('empleado.index') }}">Empleados</a></li>
                <li class="breadcrumb-item"><a href="{{ route('empleado.show', $empleado) }}">{{ $empleado->nombre }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Rendimiento</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <!-- Información del Empleado -->
    <div class="col-12 mb-4">
        <div class="card dashboard-card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h4 class="mb-1">{{ $empleado->nombre }}</h4>
                        <p class="text-muted mb-0">
                            {{ $empleado->cargo->nombre ?? 'Sin cargo' }} | 
                            {{ $empleado->equipo->nombre ?? 'Sin equipo' }}
                        </p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <div class="btn-group">
                            <a href="{{ route('empleado.show', $empleado) }}" class="btn btn-outline-primary">
                                <i class="fas fa-user me-2"></i>Ver Perfil
                            </a>
                            <a href="{{ route('empleado.tareas', $empleado) }}" class="btn btn-outline-primary">
                                <i class="fas fa-tasks me-2"></i>Ver Tareas
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas Principales -->
    <div class="col-12">
        <div class="row">
            <!-- Tareas Completadas -->
            <div class="col-md-3 mb-4">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="rounded-circle p-3 bg-primary bg-opacity-10">
                                    <i class="fas fa-check-circle fa-2x text-primary"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="text-muted mb-1">Tareas Completadas</h6>
                                <h3 class="mb-0">{{ $stats['tareas_completadas'] }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tareas a Tiempo -->
            <div class="col-md-3 mb-4">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="rounded-circle p-3 bg-success bg-opacity-10">
                                    <i class="fas fa-clock fa-2x text-success"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="text-muted mb-1">Tareas a Tiempo</h6>
                                <h3 class="mb-0">{{ $stats['tareas_a_tiempo'] }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Proyectos Participados -->
            <div class="col-md-3 mb-4">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="rounded-circle p-3 bg-info bg-opacity-10">
                                    <i class="fas fa-project-diagram fa-2x text-info"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="text-muted mb-1">Proyectos Participados</h6>
                                <h3 class="mb-0">{{ $stats['proyectos_participados'] }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Promedio Días por Tarea -->
            <div class="col-md-3 mb-4">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="rounded-circle p-3 bg-warning bg-opacity-10">
                                    <i class="fas fa-calendar-day fa-2x text-warning"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="text-muted mb-1">Promedio Días/Tarea</h6>
                                <h3 class="mb-0">{{ number_format($stats['promedio_dias_tarea'], 1) }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos de Rendimiento -->
    <div class="col-md-6 mb-4">
        <div class="card dashboard-card">
            <div class="card-header">
                <h4 class="card-title">Distribución de Tareas</h4>
            </div>
            <div class="card-body">
                <canvas id="distribucionTareas" height="300"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card dashboard-card">
            <div class="card-header">
                <h4 class="card-title">Cumplimiento de Plazos</h4>
            </div>
            <div class="card-body">
                <canvas id="cumplimientoPlazos" height="300"></canvas>
            </div>
        </div>
    </div>

    <!-- Tabla de Proyectos -->
    <div class="col-12">
        <div class="card dashboard-card">
            <div class="card-header">
                <h4 class="card-title">Participación en Proyectos</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Proyecto</th>
                                <th>Tareas Asignadas</th>
                                <th>Completadas</th>
                                <th>A Tiempo</th>
                                <th>Rendimiento</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($empleado->tareas->groupBy('proyecto_no_proyecto') as $proyectoId => $tareasPorProyecto)
                            @php
                                $proyecto = $tareasPorProyecto->first()->proyecto;
                                $totalTareas = $tareasPorProyecto->count();
                                $tareasCompletadas = $tareasPorProyecto->where('estado.nombre', 'FINALIZADA')->count();
                                $tareasATiempo = $tareasPorProyecto->where('estado.nombre', 'FINALIZADA')
                                    ->where('fecha_fin', '>=', DB::raw('fecha_inicio'))
                                    ->count();
                                $rendimiento = $totalTareas > 0 ? ($tareasATiempo / $totalTareas) * 100 : 0;
                            @endphp
                            <tr>
                                <td>
                                    <a href="{{ route('proyecto.show', $proyecto) }}">
                                        {{ $proyecto->nombre }}
                                    </a>
                                </td>
                                <td>{{ $totalTareas }}</td>
                                <td>{{ $tareasCompletadas }}</td>
                                <td>{{ $tareasATiempo }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1" style="height: 5px;">
                                            <div class="progress-bar bg-{{ $rendimiento >= 80 ? 'success' : ($rendimiento >= 60 ? 'warning' : 'danger') }}" 
                                                 role="progressbar" 
                                                 style="width: {{ $rendimiento }}%" 
                                                 aria-valuenow="{{ $rendimiento }}" 
                                                 aria-valuemin="0" 
                                                 aria-valuemax="100">
                                            </div>
                                        </div>
                                        <span class="ms-2">{{ number_format($rendimiento, 1) }}%</span>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Datos para el gráfico de distribución de tareas
    const distribucionCtx = document.getElementById('distribucionTareas').getContext('2d');
    new Chart(distribucionCtx, {
        type: 'doughnut',
        data: {
            labels: ['Completadas', 'En Proceso', 'Pendientes'],
            datasets: [{
                data: [
                    {{ $stats['tareas_completadas'] }},
                    {{ $empleado->tareas->whereIn('estado.nombre', ['EN PROCESO'])->count() }},
                    {{ $empleado->tareas->whereIn('estado.nombre', ['CREADA'])->count() }}
                ],
                backgroundColor: [
                    'rgba(40, 167, 69, 0.8)',
                    'rgba(0, 123, 255, 0.8)',
                    'rgba(255, 193, 7, 0.8)'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Datos para el gráfico de cumplimiento de plazos
    const cumplimientoCtx = document.getElementById('cumplimientoPlazos').getContext('2d');
    new Chart(cumplimientoCtx, {
        type: 'bar',
        data: {
            labels: ['A Tiempo', 'Con Retraso'],
            datasets: [{
                label: 'Tareas Completadas',
                data: [
                    {{ $stats['tareas_a_tiempo'] }},
                    {{ $stats['tareas_completadas'] - $stats['tareas_a_tiempo'] }}
                ],
                backgroundColor: [
                    'rgba(40, 167, 69, 0.8)',
                    'rgba(220, 53, 69, 0.8)'
                ]
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    // Inicializar DataTable
    $(document).ready(function() {
        $('.table').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json'
            },
            order: [[4, 'desc']],
            pageLength: 10,
            responsive: true
        });
    });
</script>
@endpush

@endsection