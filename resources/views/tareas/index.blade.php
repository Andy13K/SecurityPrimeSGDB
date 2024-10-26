@extends('layouts.app')

@section('title', 'Gestión de Tareas')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Tareas</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tareas</li>
            </ol>
        </nav>
    </div>
    <div class="ms-auto">
        <a href="{{ route('tarea.create') }}" class="btn btn-custom-primary">
            <i class="fas fa-plus me-2"></i>Nueva Tarea
        </a>
    </div>
    <br>
</div>

<div class="row">
    <!-- Tarjetas de Resumen -->
    <div class="col-12 mb-4">
        <div class="row">
            <div class="col-md-3">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="rounded-circle p-3 bg-primary bg-opacity-10">
                                    <i class="fas fa-tasks fa-2x text-primary"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="text-muted mb-1">Total Tareas</h6>
                                <h3 class="mb-0">{{ $tareas->count() }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
                                <h3 class="mb-0">{{ $tareas->where('estado.nombre', 'FINALIZADA')->count() }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
                                <h6 class="text-muted mb-1">En Proceso</h6>
                                <h3 class="mb-0">{{ $tareas->where('estado.nombre', 'EN PROCESO')->count() }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
                                <h3 class="mb-0">{{ $tareas->filter->estaAtrasada()->count() }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Tareas -->
    <div class="col-12">
        <div class="card dashboard-card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Descripción</th>
                                <th>Proyecto</th>
                                <th>Empleado</th>
                                <th>Fecha Inicio</th>
                                <th>Fecha Fin</th>
                                <th>Estado</th>
                                <th>Prioridad</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tareas as $tarea)
                            <tr class="{{ $tarea->estaAtrasada() ? 'table-danger' : '' }}">
                                <td>{{ $tarea->no_tarea }}</td>
                                <td>
                                    <div class="text-wrap" style="max-width: 200px;">
                                        {{ Str::limit($tarea->descripcion, 50) }}
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('proyecto.show', $tarea->proyecto) }}">
                                        {{ $tarea->proyecto->nombre }}
                                    </a>
                                </td>
                                <td>
                                    @if($tarea->empleado)
                                        <a href="{{ route('empleado.show', $tarea->empleado) }}">
                                            {{ $tarea->empleado->nombre }}
                                        </a>
                                    @else
                                        <span class="text-muted">Sin asignar</span>
                                    @endif
                                </td>
                                <td>{{ date('d/m/Y', strtotime($tarea->fecha_inicio)) }}</td>
                                <td>{{ date('d/m/Y', strtotime($tarea->fecha_fin)) }}</td>
                                <td>
                                    <span class="badge bg-{{ $tarea->estado->nombre === 'FINALIZADA' ? 'success' : 
                                        ($tarea->estado->nombre === 'EN PROCESO' ? 'primary' : 
                                        ($tarea->estaAtrasada() ? 'danger' : 'warning')) }}">
                                        {{ $tarea->getEstadoFormateado() }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $tarea->getPrioridad() === 'Alta' ? 'danger' : 
                                        ($tarea->getPrioridad() === 'Media' ? 'warning' : 'info') }}">
                                        {{ $tarea->getPrioridad() }}
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
                                        @if($tarea->estaFinalizada() && $tarea->comprobacion)
                                            <a href="{{ route('tarea.evidencia', $tarea) }}" 
                                               class="btn btn-sm btn-success"
                                               data-bs-toggle="tooltip" 
                                               title="Ver evidencia">
                                                <i class="fas fa-file-alt"></i>
                                            </a>
                                        @endif
                                        @if(!$tarea->estaFinalizada())
                                            <button type="button"
                                                    class="btn btn-sm btn-warning"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#asignarModal"
                                                    data-tarea-id="{{ $tarea->no_tarea }}"
                                                    title="Asignar empleado">
                                                <i class="fas fa-user-plus"></i>
                                            </button>
                                        @endif
                                        <form action="{{ route('tarea.destroy', $tarea) }}" 
                                              method="POST" 
                                              class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-danger delete-confirm"
                                                    data-bs-toggle="tooltip" 
                                                    title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
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

<!-- Modal Asignar Empleado -->
<div class="modal fade" id="asignarModal" tabindex="-1" aria-labelledby="asignarModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="asignarForm" action="" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="asignarModalLabel">Asignar Empleado</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="empleado_no" class="form-label">Seleccione el empleado</label>
                        <select class="form-select" id="empleado_no" name="empleado_no" required>
                            <option value="">Seleccione un empleado</option>
                            @foreach($empleados as $empleado)
                                <option value="{{ $empleado->no_empleado }}">
                                    {{ $empleado->nombre }} - {{ $empleado->cargo->nombre ?? 'Sin cargo' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Asignar</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Inicializar DataTable
        $('.table').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json'
            },
            order: [[0, 'desc']],
            pageLength: 10,
            responsive: true
        });

        // Inicializar tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        // Confirmar eliminación
        $('.delete-confirm').click(function(e) {
            e.preventDefault();
            if (confirm('¿Está seguro de que desea eliminar esta tarea?')) {
                $(this).closest('form').submit();
            }
        });

        // Modal de asignación
        $('#asignarModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var tareaId = button.data('tarea-id');
            var form = $(this).find('#asignarForm');
            form.attr('action', '/tareas/' + tareaId + '/asignar');
        });
    });
</script>
@endpush

@endsection