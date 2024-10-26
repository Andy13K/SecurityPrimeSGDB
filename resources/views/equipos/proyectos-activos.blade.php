@extends('layouts.app')

@section('title', 'Proyectos Activos')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Proyectos Activos - {{ $equipo_trabajo->nombre }}</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('equipo_trabajo.index') }}">Equipos</a></li>
                <li class="breadcrumb-item"><a href="{{ route('equipo_trabajo.show', $equipo_trabajo) }}">{{ $equipo_trabajo->nombre }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Proyectos Activos</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <!-- Resumen del Equipo -->
    <div class="col-12 col-lg-4">
        <div class="card dashboard-card">
            <div class="card-header">
                <h4 class="card-title"><i class="fas fa-info-circle me-2"></i>Resumen del Equipo</h4>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <span class="text-muted">Supervisor:</span>
                        <span class="fw-bold float-end">{{ $equipo_trabajo->supervisor }}</span>
                    </li>
                    <li class="list-group-item">
                        <span class="text-muted">Proyectos Activos:</span>
                        <span class="badge bg-primary float-end">{{ $proyectos->count() }}</span>
                    </li>
                    <li class="list-group-item">
                        <span class="text-muted">Miembros:</span>
                        <span class="badge bg-info float-end">{{ $equipo_trabajo->empleados->count() }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Lista de Proyectos -->
    <div class="col-12 col-lg-8">
        <div class="card dashboard-card">
            <div class="card-header">
                <h4 class="card-title"><i class="fas fa-project-diagram me-2"></i>Proyectos en Curso</h4>
            </div>
            <div class="card-body">
                @if($proyectos->isEmpty())
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-clipboard-list fa-3x mb-3"></i>
                        <h5>No hay proyectos activos</h5>
                        <p>Este equipo no tiene proyectos en curso actualmente.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Proyecto</th>
                                    <th>Cliente</th>
                                    <th>Inicio</th>
                                    <th>Fin Previsto</th>
                                    <th>Estado</th>
                                    <th>Progreso</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($proyectos as $proyecto)
                                @php
                                    $totalTareas = $proyecto->tareas->count();
                                    $tareasCompletadas = $proyecto->tareas->filter(function($tarea) {
                                        return $tarea->estado->nombre === 'FINALIZADA';
                                    })->count();
                                    $progreso = $totalTareas > 0 ? ($tareasCompletadas / $totalTareas) * 100 : 0;
                                @endphp
                                <tr>
                                    <td>{{ $proyecto->nombre }}</td>
                                    <td>{{ $proyecto->cliente->nombre }}</td>
                                    <td>{{ optional(\Carbon\Carbon::parse($proyecto->fecha_inicio))->format('d/m/Y') }}</td>
                                    <td>{{ optional(\Carbon\Carbon::parse($proyecto->fecha_fin))->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $proyecto->estado->first()->nombre === 'EN PROCESO' ? 'primary' : 'warning' }}">
                                            {{ $proyecto->estado->first()->nombre }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar bg-success" 
                                                 role="progressbar" 
                                                 style="width: {{ $progreso }}%"
                                                 aria-valuenow="{{ $progreso }}" 
                                                 aria-valuemin="0" 
                                                 aria-valuemax="100">
                                                {{ number_format($progreso, 1) }}%
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('proyecto.show', $proyecto) }}" 
                                               class="btn btn-sm btn-info" 
                                               title="Ver detalles">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('proyecto.tareas', $proyecto) }}" 
                                               class="btn btn-sm btn-primary" 
                                               title="Ver tareas">
                                                <i class="fas fa-tasks"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Resumen de Tareas -->
                    <div class="mt-4">
                        <h5><i class="fas fa-tasks me-2"></i>Resumen de Tareas</h5>
                        <div class="row g-3">
                            @foreach($proyectos as $proyecto)
                            <div class="col-md-6">
                                <div class="card border">
                                    <div class="card-body">
                                        <h6 class="card-title">{{ $proyecto->nombre }}</h6>
                                        <div class="mb-2">
                                            <small class="text-muted">Tareas Pendientes:</small>
                                            <span class="badge bg-warning float-end">
                                                {{ $proyecto->tareas->whereIn('estado.nombre', ['CREADA', 'ASIGNADA'])->count() }}
                                            </span>
                                        </div>
                                        <div>
                                            <small class="text-muted">Tareas Completadas:</small>
                                            <span class="badge bg-success float-end">
                                                {{ $proyecto->tareas->where('estado.nombre', 'FINALIZADA')->count() }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                @endif
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

@push('scripts')
<script>
    $(document).ready(function() {
        // Tooltip initialization
        $('[data-bs-toggle="tooltip"]').tooltip();
        
        // DataTable initialization if needed
        if ($('.table').length > 0) {
            $('.table').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
                },
                order: [[5, 'desc']], // Ordenar por progreso descendente
                responsive: true
            });
        }
    });
</script>
@endpush

@endsection