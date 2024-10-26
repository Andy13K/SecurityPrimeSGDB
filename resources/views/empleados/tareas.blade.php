@extends('layouts.app')

@section('title', 'Tareas del Empleado')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Tareas del Empleado</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('empleado.index') }}">Empleados</a></li>
                <li class="breadcrumb-item"><a href="{{ route('empleado.show', $empleado) }}">{{ $empleado->nombre }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tareas</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <!-- Resumen del Empleado -->
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
                    <div class="col-md-6">
                        <div class="row text-center">
                            <div class="col-4">
                                <h5 class="mb-1">{{ $tareas->has('pendientes') ? $tareas->get('pendientes')->count() : 0 }}</h5>
                                <small class="text-warning">Pendientes</small>
                            </div>
                            <div class="col-4">
                                <h5 class="mb-1">{{ $tareas->has('atrasadas') ? $tareas->get('atrasadas')->count() : 0 }}</h5>
                                <small class="text-danger">Atrasadas</small>
                            </div>
                            <div class="col-4">
                                <h5 class="mb-1">{{ $tareas->has('completadas') ? $tareas->get('completadas')->count() : 0 }}</h5>
                                <small class="text-success">Completadas</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs de Tareas -->
    <div class="col-12">
        <div class="card dashboard-card">
            <div class="card-body">
                <ul class="nav nav-tabs" id="tareasTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" 
                                id="pendientes-tab" 
                                data-bs-toggle="tab" 
                                data-bs-target="#pendientes" 
                                type="button" 
                                role="tab" 
                                aria-controls="pendientes" 
                                aria-selected="true">
                            <i class="fas fa-clock me-2"></i>Pendientes
                            <span class="badge bg-warning ms-2">{{ $tareas->has('pendientes') ? $tareas->get('pendientes')->count() : 0 }}</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" 
                                id="atrasadas-tab" 
                                data-bs-toggle="tab" 
                                data-bs-target="#atrasadas" 
                                type="button" 
                                role="tab" 
                                aria-controls="atrasadas" 
                                aria-selected="false">
                            <i class="fas fa-exclamation-triangle me-2"></i>Atrasadas
                            <span class="badge bg-danger ms-2">{{ $tareas->has('atrasadas') ? $tareas->get('atrasadas')->count() : 0 }}</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" 
                                id="completadas-tab" 
                                data-bs-toggle="tab" 
                                data-bs-target="#completadas" 
                                type="button" 
                                role="tab" 
                                aria-controls="completadas" 
                                aria-selected="false">
                            <i class="fas fa-check-circle me-2"></i>Completadas
                            <span class="badge bg-success ms-2">{{ $tareas->has('completadas') ? $tareas->get('completadas')->count() : 0 }}</span>
                        </button>
                    </li>
                </ul>

                <div class="tab-content mt-4" id="tareasTabsContent">
                    <!-- Tareas Pendientes -->
                    <div class="tab-pane fade show active" id="pendientes" role="tabpanel" aria-labelledby="pendientes-tab">
                        @if($tareas->has('pendientes') && $tareas->get('pendientes')->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Tarea</th>
                                            <th>Proyecto</th>
                                            <th>Fecha Inicio</th>
                                            <th>Fecha Fin</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
    @foreach($tareas->get('pendientes') as $tarea)
        <tr>
            <td>{{ $tarea->descripcion }}</td>
            <td>
                <a href="{{ route('proyecto.show', $tarea->proyecto) }}">
                    {{ $tarea->proyecto->nombre }}
                </a>
            </td>
            <td>{{ optional(\Carbon\Carbon::parse($tarea->fecha_inicio))->format('d/m/Y') }}</td>
            <td>{{ optional(\Carbon\Carbon::parse($tarea->fecha_fin))->format('d/m/Y') }}</td>
            <td>
                <span class="badge bg-primary">{{ $tarea->estado->nombre }}</span>
            </td>
            <td>
                <!-- Acciones -->
                <div class="d-flex gap-2">
                    <a href="{{ route('tarea.show', $tarea) }}" class="btn btn-sm btn-info">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="{{ route('tarea.edit', $tarea) }}" class="btn btn-sm btn-warning">
                        <i class="fas fa-edit"></i>
                    </a>
                </div>
            </td>
        </tr>
    @endforeach
</tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-tasks fa-3x text-muted mb-3"></i>
                                <p>No hay tareas pendientes</p>
                            </div>
                        @endif
                    </div>

                    <!-- Tareas Atrasadas -->
                    <div class="tab-pane fade" id="atrasadas" role="tabpanel" aria-labelledby="atrasadas-tab">
                        @if($tareas->has('atrasadas') && $tareas->get('atrasadas')->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Tarea</th>
                                            <th>Proyecto</th>
                                            <th>Fecha Inicio</th>
                                            <th>Fecha Fin</th>
                                            <th>Días de Retraso</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($tareas->get('atrasadas') as $tarea)
                                            <tr>
                                                <td>{{ $tarea->descripcion }}</td>
                                                <td>
                                                    <a href="{{ route('proyecto.show', $tarea->proyecto) }}">
                                                        {{ $tarea->proyecto->nombre }}
                                                    </a>
                                                </td>
                                                <td>{{ $tarea->fecha_inicio->format('d/m/Y') }}</td>
                                                <td>{{ $tarea->fecha_fin->format('d/m/Y') }}</td>
                                                <td>
                                                    <span class="badge bg-danger">
                                                        {{ now()->diffInDays($tarea->fecha_fin) }} días
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="btn-group">
                                                        <a href="{{ route('tarea.show', $tarea) }}" 
                                                           class="btn btn-sm btn-info" 
                                                           data-bs-toggle="tooltip" 
                                                           title="Ver detalles">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('tarea.edit', $tarea) }}" 
                                                           class="btn btn-sm btn-primary"
                                                           data-bs-toggle="tooltip" 
                                                           title="Editar">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-clock fa-3x text-muted mb-3"></i>
                                <p>No hay tareas atrasadas</p>
                            </div>
                        @endif
                    </div>

                    <!-- Tareas Completadas -->
                    <div class="tab-pane fade" id="completadas" role="tabpanel" aria-labelledby="completadas-tab">
                        @if($tareas->has('completadas') && $tareas->get('completadas')->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Tarea</th>
                                            <th>Proyecto</th>
                                            <th>Fecha Inicio</th>
                                            <th>Fecha Fin</th>
                                            <th>Duración</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($tareas->get('completadas') as $tarea)
                                            <tr>
                                                <td>{{ $tarea->descripcion }}</td>
                                                <td>
                                                    <a href="{{ route('proyecto.show', $tarea->proyecto) }}">
                                                        {{ $tarea->proyecto->nombre }}
                                                    </a>
                                                </td>
                                                <td>{{ $tarea->fecha_inicio ? \Carbon\Carbon::parse($tarea->fecha_inicio)->format('d/m/Y') : '' }}</td>
<td>{{ $tarea->fecha_fin ? \Carbon\Carbon::parse($tarea->fecha_fin)->format('d/m/Y') : '' }}</td>
<td>{{ $tarea->fecha_inicio && $tarea->fecha_fin ? \Carbon\Carbon::parse($tarea->fecha_inicio)->diffInDays($tarea->fecha_fin) . ' días' : 'N/A' }}</td>
                                                <td>
                                                    <div class="btn-group">
                                                        <a href="{{ route('tarea.show', $tarea) }}" 
                                                           class="btn btn-sm btn-info" 
                                                           data-bs-toggle="tooltip" 
                                                           title="Ver detalles">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-check-circle fa-3x text-muted mb-3"></i>
                                <p>No hay tareas completadas</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Inicializar tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        // Inicializar DataTables
        $('.table').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json'
            },
            order: [[3, 'asc']],
            pageLength: 10,
            responsive: true
        });
    });
</script>
@endpush

@endsection