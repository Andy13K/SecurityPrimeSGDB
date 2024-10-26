@extends('layouts.app')

@section('title', $equipo_trabajo->nombre)

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">{{ $equipo_trabajo->nombre }}</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('equipo_trabajo.index') }}">Equipos</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $equipo_trabajo->nombre }}</li>
                </ol>
            </nav>
        </div>
        <div class="btn-group">
            <a href="{{ route('equipo_trabajo.edit', $equipo_trabajo) }}" class="btn btn-warning">
                <i class="fas fa-edit me-2"></i>Editar
            </a>
            <a href="{{ route('equipo_trabajo.rendimiento', $equipo_trabajo) }}" class="btn btn-info">
                <i class="fas fa-chart-line me-2"></i>Rendimiento
            </a>
        </div>
    </div>
</div>

<div class="row">
    <!-- Información del Equipo -->
    <div class="col-12 col-lg-4">
        <div class="card dashboard-card">
            <div class="card-header">
                <h4 class="card-title"><i class="fas fa-info-circle me-2"></i>Información del Equipo</h4>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <span class="text-muted">Supervisor:</span>
                        <span class="fw-bold float-end">{{ $equipo_trabajo->supervisor }}</span>
                    </li>
                    <li class="list-group-item">
                        <span class="text-muted">Miembros:</span>
                        <span class="badge bg-primary float-end">{{ $equipo_trabajo->empleados->count() }}</span>
                    </li>
                    <li class="list-group-item">
                        <span class="text-muted">Proyectos Activos:</span>
                        <span class="badge bg-info float-end">{{ $equipo_trabajo->proyectos->count() }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Miembros del Equipo -->
    <div class="col-12 col-lg-8">
        <div class="card dashboard-card">
            <div class="card-header">
                <h4 class="card-title"><i class="fas fa-users me-2"></i>Miembros del Equipo</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Empleado</th>
                                <th>Cargo</th>
                                <th>Tareas Pendientes</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($equipo_trabajo->empleados as $empleado)
                            <tr>
                                <td>{{ $empleado->nombre }}</td>
                                <td>{{ $empleado->cargo->nombre ?? 'Sin cargo' }}</td>
                                <td>
                                    <span class="badge bg-warning">
                                        {{ $empleado->tareas()->whereHas('estado', function($q) {
                                            $q->where('nombre', '!=', 'FINALIZADA');
                                        })->count() }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('empleado.show', $empleado) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">
                                    No hay empleados asignados a este equipo
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Proyectos Activos -->
        <div class="card dashboard-card mt-4">
            <div class="card-header">
                <h4 class="card-title"><i class="fas fa-project-diagram me-2"></i>Proyectos Activos</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Proyecto</th>
                                <th>Cliente</th>
                                <th>Fecha Inicio</th>
                                <th>Fecha Fin</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($equipo_trabajo->proyectos as $proyecto)
                            <tr>
                                <td>{{ $proyecto->nombre }}</td>
                                <td>{{ $proyecto->cliente->nombre }}</td>
                                <td>{{ $proyecto->fecha_inicio ? \Carbon\Carbon::parse($proyecto->fecha_inicio)->format('d/m/Y') : 'N/A' }}</td>
                                <td>{{ $proyecto->fecha_fin ? \Carbon\Carbon::parse($proyecto->fecha_fin)->format('d/m/Y') : 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-{{ $proyecto->estado->first()->nombre === 'FINALIZADO' ? 'success' : 'primary' }}">
                                        {{ $proyecto->estado->first()->nombre }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('proyecto.show', $proyecto) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">
                                    No hay proyectos activos para este equipo
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@endsection