@extends('layouts.app')

@section('title', 'Detalles de Tarea')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Detalles de Tarea</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('tarea.index') }}">Tareas</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detalles</li>
            </ol>
        </nav>
    </div>
    <div class="ms-auto">
        @if(!$tarea->estaFinalizada())
            <button type="button" class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#finalizarModal">
                <i class="fas fa-check-circle me-2"></i>Finalizar Tarea
            </button>
        @endif
        <a href="{{ route('tarea.edit', $tarea) }}" class="btn btn-primary">
            <i class="fas fa-edit me-2"></i>Editar
        </a>
    </div>
</div>

<div class="row">
    <!-- Detalles de la Tarea -->
    <div class="col-lg-8">
        <div class="card dashboard-card mb-4">
            <div class="card-header">
                <h4 class="card-title"><i class="fas fa-tasks me-2"></i>Información de la Tarea</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label text-muted">Descripción</label>
                        <div class="p-3 bg-light rounded">
                            {{ $tarea->descripcion }}
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Proyecto</label>
                        <div>
                            <a href="{{ route('proyecto.show', $tarea->proyecto) }}" class="text-decoration-none">
                                <i class="fas fa-project-diagram me-2"></i>{{ $tarea->proyecto->nombre }}
                            </a>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Empleado Asignado</label>
                        <div>
                            <a href="{{ route('empleado.show', $tarea->empleado) }}" class="text-decoration-none">
                                <i class="fas fa-user me-2"></i>{{ $tarea->empleado->nombre }}
                            </a>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Fecha de Inicio</label>
                        <div>
                            {{ date('d/m/Y', strtotime($tarea->fecha_inicio)) }}
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Fecha de Finalización</label>
                        <div>
                        {{ \Carbon\Carbon::parse($tarea->fecha_fin)->format('d/m/Y') }}
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Estado</label>
                        <div>
                            <span class="badge bg-{{ $tarea->estado->nombre === 'FINALIZADA' ? 'success' : 
                                ($tarea->estado->nombre === 'EN PROCESO' ? 'primary' : 
                                ($tarea->estaAtrasada() ? 'danger' : 'warning')) }}">
                                {{ $tarea->getEstadoFormateado() }}
                            </span>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Prioridad</label>
                        <div>
                            <span class="badge bg-{{ $tarea->getPrioridad() === 'Alta' ? 'danger' : 
                                ($tarea->getPrioridad() === 'Media' ? 'warning' : 'info') }}">
                                {{ $tarea->getPrioridad() }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($tarea->comprobacion)
        <div class="card dashboard-card">
            <div class="card-header">
                <h4 class="card-title"><i class="fas fa-file-alt me-2"></i>Evidencia</h4>
            </div>
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <p class="mb-0">{{ $tarea->comprobacion }}</p>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('tarea.evidencia', $tarea) }}" 
                           class="btn btn-primary" 
                           target="_blank">
                            <i class="fas fa-download me-2"></i>Ver Evidencia
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Información Adicional -->
    <div class="col-lg-4">
        <div class="card dashboard-card mb-4">
            <div class="card-header">
                <h4 class="card-title"><i class="fas fa-info-circle me-2"></i>Información Adicional</h4>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Duración Estimada
                        <span class="badge bg-primary rounded-pill">{{ $tarea->getDuracionEstimada() }} días</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Días Restantes
                        <span class="badge bg-{{ $tarea->getDiasRestantes() < 0 ? 'danger' : 
                            ($tarea->getDiasRestantes() <= 2 ? 'warning' : 'success') }} rounded-pill">
                            {{ $tarea->getDiasRestantes() }} días
                        </span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Estado
                        <span class="badge bg-{{ $tarea->estaFinalizada() ? 'success' : 'primary' }}">
                            {{ $tarea->estado->nombre }}
                        </span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Acciones -->
        <div class="card dashboard-card">
            <div class="card-header">
                <h4 class="card-title"><i class="fas fa-cogs me-2"></i>Acciones</h4>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @if(!$tarea->estaFinalizada())
                        <button type="button" 
                                class="btn btn-success"
                                data-bs-toggle="modal" 
                                data-bs-target="#finalizarModal">
                            <i class="fas fa-check-circle me-2"></i>Finalizar Tarea
                        </button>
                        <a href="{{ route('tarea.edit', $tarea) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>Editar Tarea
                        </a>
                    @endif
                    <form action="{{ route('tarea.destroy', $tarea) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100 delete-confirm">
                            <i class="fas fa-trash me-2"></i>Eliminar Tarea
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Finalizar Tarea -->
@if(!$tarea->estaFinalizada())
<div class="modal fade" id="finalizarModal" tabindex="-1" aria-labelledby="finalizarModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('tarea.finalizar', $tarea) }}" method="POST" enctype="multipart/form-data">
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
@endif

@push('scripts')
<script>
    // Confirmar eliminación
    $('.delete-confirm').click(function(e) {
        e.preventDefault();
        if (confirm('¿Está seguro de que desea eliminar esta tarea?')) {
            $(this).closest('form').submit();
        }
    });
</script>
@endpush

@endsection