@extends('layouts.app')

@section('title', 'Rendimiento del Equipo')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Rendimiento - {{ $equipo_trabajo->nombre }}</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('equipo_trabajo.index') }}">Equipos</a></li>
                <li class="breadcrumb-item"><a href="{{ route('equipo_trabajo.show', $equipo_trabajo) }}">{{ $equipo_trabajo->nombre }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Rendimiento</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <!-- Estadísticas Generales -->
    <div class="col-12 col-lg-4">
        <div class="card dashboard-card">
            <div class="card-header">
                <h4 class="card-title"><i class="fas fa-chart-pie me-2"></i>Estadísticas Generales</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Proyectos Completados -->
                    <div class="col-12 mb-4">
                        <div class="p-3 bg-success bg-opacity-10 rounded">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <i class="fas fa-check-circle fa-2x text-success"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Proyectos Completados</h6>
                                    <h3 class="mb-0">{{ $stats['proyectos_completados'] }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tareas Completadas -->
                    <div class="col-12 mb-4">
                        <div class="p-3 bg-primary bg-opacity-10 rounded">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <i class="fas fa-tasks fa-2x text-primary"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Tareas Completadas</h6>
                                    <h3 class="mb-0">{{ $stats['tareas_completadas'] }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Empleados Activos -->
                    <div class="col-12">
                        <div class="p-3 bg-info bg-opacity-10 rounded">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <i class="fas fa-users fa-2x text-info"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Empleados Activos</h6>
                                    <h3 class="mb-0">{{ $stats['empleados_activos'] }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Rendimiento por Empleado -->
    <div class="col-12 col-lg-8">
        <div class="card dashboard-card">
            <div class="card-header">
                <h4 class="card-title"><i class="fas fa-user-check me-2"></i>Rendimiento por Empleado</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Empleado</th>
                                <th>Cargo</th>
                                <th>Tareas Completadas</th>
                                <th>Tareas Pendientes</th>
                                <th>Última Actividad</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($equipo_trabajo->empleados as $empleado)
                            @php
                                $ultimaTarea = $empleado->tareas()
                                    ->whereHas('estado', function($q) {
                                        $q->where('nombre', 'FINALIZADA');
                                    })
                                    ->latest('updated_at')
                                    ->first();
                            @endphp
                            <tr>
                                <td>{{ $empleado->nombre }}</td>
                                <td>{{ $empleado->cargo->nombre ?? 'Sin cargo' }}</td>
                                <td>
                                    <span class="badge bg-success">
                                        {{ $empleado->tareas()->whereHas('estado', function($q) {
                                            $q->where('nombre', 'FINALIZADA');
                                        })->count() }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-warning">
                                        {{ $empleado->tareas()->whereHas('estado', function($q) {
                                            $q->where('nombre', '!=', 'FINALIZADA');
                                        })->count() }}
                                    </span>
                                </td>
                                <td>
                                    {{ $ultimaTarea ? \Carbon\Carbon::parse($ultimaTarea->updated_at)->format('d/m/Y H:i') : 'Sin actividad' }}
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

@endsection