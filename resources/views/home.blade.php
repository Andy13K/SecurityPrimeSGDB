@extends('layouts.app')

@section('content')
<h1 class="mb-4">Panel de Control</h1>

<div class="row g-4">
    <!-- Tarjeta de Proyectos -->
    <div class="col-md-4 col-lg-3">
        <div class="dashboard-card card-proyecto">
            <div class="card-content">
                <i class="fas fa-project-diagram card-icon"></i>
                <h3 class="card-title">Proyectos Activos</h3>
                <p class="card-value">{{ $stats['proyectos_activos'] }}</p>
            </div>
        </div>
    </div>
    
    <!-- Tarjeta de Clientes -->
    <div class="col-md-4 col-lg-3">
        <div class="dashboard-card card-cliente">
            <div class="card-content">
                <i class="fas fa-users card-icon"></i>
                <h3 class="card-title">Total Clientes</h3>
                <p class="card-value">{{ $stats['total_clientes'] }}</p>
            </div>
        </div>
    </div>
    
    <!-- Tarjeta de Tareas -->
    <div class="col-md-4 col-lg-3">
        <div class="dashboard-card card-tarea">
            <div class="card-content">
                <i class="fas fa-tasks card-icon"></i>
                <h3 class="card-title">Tareas Pendientes</h3>
                <p class="card-value">{{ $stats['tareas_pendientes'] }}</p>
            </div>
        </div>
    </div>

    <!-- Tarjeta de Empleados -->
    <div class="col-md-4 col-lg-3">
        <div class="dashboard-card card-empleado">
            <div class="card-content">
                <i class="fas fa-user-tie card-icon"></i>
                <h3 class="card-title">Total Empleados</h3>
                <p class="card-value">{{ $stats['total_empleados'] }}</p>
            </div>
        </div>
    </div>

    <!-- Tarjeta de Equipos -->
    <div class="col-md-4 col-lg-3">
        <div class="dashboard-card card-equipo">
            <div class="card-content">
                <i class="fas fa-users-cog card-icon"></i>
                <h3 class="card-title">Equipos Activos</h3>
                <p class="card-value">{{ $stats['equipos_activos'] }}</p>
            </div>
        </div>
    </div>

    <!-- Tarjeta de Recursos -->
    <div class="col-md-4 col-lg-3">
        <div class="dashboard-card card-recurso">
            <div class="card-content">
                <i class="fas fa-box-open card-icon"></i>
                <h3 class="card-title">Recursos Disponibles</h3>
                <p class="card-value">{{ $stats['recursos_disponibles'] }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Tabla de Proyectos Recientes -->
<div class="recent-projects">
    <h3>Proyectos Recientes</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Nombre del Proyecto</th>
                <th>Cliente</th>
                <th>Fecha de Inicio</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($proyectosRecientes as $proyecto)
            <tr>
                <td>{{ $proyecto->nombre }}</td>
                <td>{{ $proyecto->cliente->nombre }}</td>
                <td>{{ \Carbon\Carbon::parse($proyecto->fecha_inicio)->format('Y-m-d') }}</td>
                <td>
                    @foreach($proyecto->estado as $estado)
                    <span class="badge bg-{{ $estadosColores[$estado->nombre] }}">
                        {{ $estado->nombre }}
                    </span>
                    @endforeach
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Gráfico y Tareas Pendientes -->
<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                Progreso de Proyectos
            </div>
            <div class="card-body">
                <canvas id="projectProgressChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                Tareas Pendientes Prioritarias
            </div>
            <div class="card-body">
                <ul class="list-group">
                    @foreach($tareasPendientes as $tarea)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $tarea['descripcion'] }}
                        <div>
                            <small class="text-muted">{{ $tarea['empleado'] }}</small>
                            <span class="badge bg-{{ $tarea['clase'] }} rounded-pill">
                                {{ $tarea['estado'] }}
                            </span>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Gráfico de Progreso de Proyectos
    const ctx = document.getElementById('projectProgressChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode(array_keys($estadisticasProyectos)) !!},
            datasets: [{
                data: {!! json_encode(array_values($estadisticasProyectos)) !!},
                backgroundColor: [
                    'rgba(75, 192, 192, 0.8)',
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(255, 206, 86, 0.8)'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                },
                title: {
                    display: true,
                    text: 'Estado de Proyectos'
                }
            }
        }
    });
</script>
@endpush
@endsection