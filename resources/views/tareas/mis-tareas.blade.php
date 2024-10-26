@extends('layouts.app')

@section('title', 'Mis Tareas')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Mis Tareas</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Mis Tareas</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <!-- Resumen de Tareas -->
    <div class="col-12 mb-4">
        <div class="row">
            <!-- Tareas Pendientes -->
            <div class="col-md-3">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="rounded-circle p-3 bg-warning bg-opacity-10">
                                    <i class="fas fa-clock fa-2x text-warning"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="text-muted mb-1">Pendientes</h6>
                                <h3 class="mb-0">{{ $tareas->has('pendientes') ? $tareas->get('pendientes')->count() : 0 }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tareas Urgentes -->
            <div class="col-md-3">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="rounded-circle p-3 bg-danger bg-opacity-10">
                                    <i class="fas fa-exclamation-circle fa-2x text-danger"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="text-muted mb-1">Urgentes</h6>
                                <h3 class="mb-0">{{ $tareas->has('urgentes') ? $tareas->get('urgentes')->count() : 0 }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tareas Atrasadas -->
            <div class="col-md-3">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="rounded-circle p-3 bg-danger bg-opacity-10">
                                    <i class="fas fa-exclamation-triangle fa-2x text-danger"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="text-muted mb-1">Atrasadas</h6>
                                <h3 class="mb-0">{{ $tareas->has('atrasadas') ? $tareas->get('atrasadas')->count() : 0 }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tareas Completadas -->
            <div class="col-md-3">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="rounded-circle p-3 bg-success bg-opacity-10">
                                    <i class="fas fa-check-circle fa-2x text-success"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="text-muted mb-1">Completadas</h6>
                                <h3 class="mb-0">{{ $tareas->has('completadas') ? $tareas->get('completadas')->count() : 0 }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Tareas -->
    <div class="col-12">
        <div class="card dashboard-card">
            <div class="card-body">
                <ul class="nav nav-tabs" role="tablist">
                    <!-- Tab Pendientes -->
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" 
                                data-bs-toggle="tab" 
                                data-bs-target="#pendientes" 
                                type="button" 
                                role="tab">
                            <i class="fas fa-clock me-2"></i>Pendientes
                            <span class="badge bg-warning ms-2">{{ $tareas->has('pendientes') ? $tareas->get('pendientes')->count() : 0 }}</span>
                        </button>
                    </li>
                    
                    <!-- Tab Urgentes -->
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" 
                                data-bs-toggle="tab" 
                                data-bs-target="#urgentes" 
                                type="button" 
                                role="tab">
                            <i class="fas fa-exclamation-circle me-2"></i>Urgentes
                            <span class="badge bg-danger ms-2">{{ $tareas->has('urgentes') ? $tareas->get('urgentes')->count() : 0 }}</span>
                        </button>
                    </li>

                    <!-- Tab Atrasadas -->
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" 
                                data-bs-toggle="tab" 
                                data-bs-target="#atrasadas" 
                                type="button" 
                                role="tab">
                            <i class="fas fa-exclamation-triangle me-2"></i>Atrasadas
                            <span class="badge bg-danger ms-2">{{ $tareas->has('atrasadas') ? $tareas->get('atrasadas')->count() : 0 }}</span>
                        </button>
                    </li>

                    <!-- Tab Completadas -->
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" 
                                data-bs-toggle="tab" 
                                data-bs-target="#completadas" 
                                type="button" 
                                role="tab">
                            <i class="fas fa-check-circle me-2"></i>Completadas
                            <span class="badge bg-success ms-2">{{ $tareas->has('completadas') ? $tareas->get('completadas')->count() : 0 }}</span>
                        </button>
                    </li>
                </ul>

                <div class="tab-content mt-4">
                    <!-- Contenido Pendientes -->
                    <div class="tab-pane fade show active" id="pendientes" role="tabpanel">
                        @if($tareas->has('pendientes') && $tareas->get('pendientes')->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Tarea</th>
                                            <th>Proyecto</th>
                                            <th>Fecha Inicio</th>
                                            <th>Fecha Fin</th>
                                            <th>Días Restantes</th>
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
                                                <td>{{ $tarea->fecha_inicio->format('d/m/Y') }}</td>
                                                <td>{{ $tarea->fecha_fin->format('d/m/Y') }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $tarea->getDiasRestantes() <= 2 ? 'warning' : 'info' }}">
                                                        {{ $tarea->getDiasRestantes() }} días
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
                                                        <button type="button"
                                                                class="btn btn-sm btn-success"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#finalizarModal"
                                                                data-tarea-id="{{ $tarea->no_tarea }}"
                                                                data-bs-toggle="tooltip" 
                                                                title="Finalizar tarea">
                                                            <i class="fas fa-check"></i>
                                                        </button>
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
                                <p>No tienes tareas pendientes</p>
                            </div>
                        @endif
                    </div>

                    <!-- Contenido Urgentes -->
                    <div class="tab-pane fade" id="urgentes" role="tabpanel">
                        @if($tareas->has('urgentes') && $tareas->get('urgentes')->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Tarea</th>
                                            <th>Proyecto</th>
                                            <th>Fecha Inicio</th>
                                            <th>Fecha Fin</th>
                                            <th>Días Restantes</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($tareas->get('urgentes') as $tarea)
                                            <tr class="table-warning">
                                                <td>{{ $tarea->descripcion }}</td>
                                                <td>
                                                    <a href="{{ route('proyecto.show', $tarea->proyecto) }}">
                                                        {{ $tarea->proyecto->nombre }}
                                                    </a>
                                                </td>
                                                <td>{{ $tarea->fecha_inicio->format('d/m/Y') }}</td>
                                                <td>{{ $tarea->fecha_fin->format('d/m/Y') }}</td>
                                                <td>
                                                    <span class="badge bg-warning">
                                                        {{ $tarea->getDiasRestantes() }} días
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
                                                        <button type="button"
                                                                class="btn btn-sm btn-success"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#finalizarModal"
                                                                data-tarea-id="{{ $tarea->no_tarea }}"
                                                                data-bs-toggle="tooltip" 
                                                                title="Finalizar tarea">
                                                            <i class="fas fa-check"></i>
                                                        </button>
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
                                <p>No tienes tareas urgentes</p>
                            </div>
                        @endif
                    </div>

                    <!-- Continúa con los paneles de Atrasadas y Completadas... -->

    <!-- Modal Finalizar Tarea -->
    <div class="modal fade" id="finalizarModal" tabindex="-1" aria-labelledby="finalizarModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="finalizarForm" action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="finalizarModalLabel">Finalizar Tarea</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="comprobacion" class="form-label">Comentarios</label>
                            <textarea class="form-control" id="comprobacion" name="comprobacion" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="evidencia" class="form-label">Evidencia</label>
                            <input type="file" class="form-control" id="evidencia" name="evidencia" required>
                            <small class="text-muted">Formatos permitidos: JPEG, PNG, JPG, PDF. Máximo 2MB.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check-circle me-2"></i>Finalizar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Inicializar DataTables
        $('.table').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json'
            },
            order: [[4, 'asc']],
            pageLength: 10,
            responsive: true
        });

        // Modal de finalización
        $('#finalizarModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var tareaId = button.data('tarea-id');
            var form = $(this).find('#finalizarForm');
            form.attr('action', '/tareas/' + tareaId + '/finalizar');
        });