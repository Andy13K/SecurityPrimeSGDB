@extends('layouts.app')

@section('title', 'Editar Tarea')

@section('content')
@php
    $estadoFinalizada = App\Models\EstadoTarea::where('nombre', 'FINALIZADA')->first()->no_estado;
@endphp

<div class="page-header">
    <div>
        <h1 class="page-title">Editar Tarea</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('tarea.index') }}">Tareas</a></li>
                <li class="breadcrumb-item"><a href="{{ route('tarea.show', $tarea) }}">{{ Str::limit($tarea->descripcion, 30) }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Editar</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-12 col-lg-8">
        <div class="card dashboard-card">
            <div class="card-header">
                <h4 class="card-title"><i class="fas fa-edit me-2"></i>Editar Tarea</h4>
            </div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('tarea.update', $tarea) }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <!-- Proyecto -->
                        <div class="col-md-12 mb-3">
                            <label class="form-label" for="proyecto_no_proyecto">Proyecto</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-project-diagram"></i>
                                </span>
                                <select class="form-select @error('proyecto_no_proyecto') is-invalid @enderror" 
                                        id="proyecto_no_proyecto" 
                                        name="proyecto_no_proyecto" 
                                        required>
                                    <option value="">Seleccione un proyecto</option>
                                    @foreach($proyectos as $proyecto)
                                        <option value="{{ $proyecto->no_proyecto }}" 
                                                {{ old('proyecto_no_proyecto', $tarea->no_proyecto) == $proyecto->no_proyecto ? 'selected' : '' }}>
                                            {{ $proyecto->nombre }}
                                            ({{ $proyecto->estado->first()->nombre ?? 'Sin estado' }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('proyecto_no_proyecto')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Empleado -->
                        <div class="col-md-12 mb-3">
                            <label class="form-label" for="empleado_no">Empleado Asignado</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-user"></i>
                                </span>
                                <select class="form-select @error('empleado_no') is-invalid @enderror" 
                                        id="empleado_no" 
                                        name="empleado_no" 
                                        required>
                                    <option value="">Seleccione un empleado</option>
                                    @foreach($empleados as $empleado)
                                        <option value="{{ $empleado->no_empleado }}" 
                                                {{ old('empleado_no', $tarea->empleado_no) == $empleado->no_empleado ? 'selected' : '' }}>
                                            {{ $empleado->nombre }} - 
                                            {{ $empleado->cargo->nombre ?? 'Sin cargo' }} 
                                            ({{ $empleado->equipo->nombre ?? 'Sin equipo' }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('empleado_no')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Estado -->
                        <div class="col-md-12 mb-3">
                            <label class="form-label" for="estado_tarea">Estado</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-tasks"></i>
                                </span>
                                <select class="form-select @error('estado_tarea') is-invalid @enderror" 
                                        id="estado_tarea" 
                                        name="estado_tarea" 
                                        required
                                        data-estado-finalizada="{{ $estadoFinalizada }}">
                                    <option value="">Seleccione un estado</option>
                                    @foreach($estados as $estado)
                                        <option value="{{ $estado->no_estado }}" 
                                                {{ old('estado_tarea', $tarea->estado_tarea) == $estado->no_estado ? 'selected' : '' }}>
                                            {{ $estado->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('estado_tarea')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Descripción -->
                        <div class="col-12 mb-3">
                            <label class="form-label" for="descripcion">Descripción</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-align-left"></i>
                                </span>
                                <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                                          id="descripcion" 
                                          name="descripcion" 
                                          rows="3" 
                                          required>{{ old('descripcion', $tarea->descripcion) }}</textarea>
                                @error('descripcion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Fechas -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="fecha_inicio">Fecha de Inicio</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-calendar-alt"></i>
                                </span>
                                <input type="date" 
                                       class="form-control @error('fecha_inicio') is-invalid @enderror" 
                                       id="fecha_inicio" 
                                       name="fecha_inicio" 
                                       value="{{ old('fecha_inicio', optional(\Carbon\Carbon::parse($tarea->fecha_inicio))->format('Y-m-d')) }}"
                                       required>
                                @error('fecha_inicio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="fecha_fin">Fecha de Finalización</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-calendar-check"></i>
                                </span>
                                <input type="date" 
                                       class="form-control @error('fecha_fin') is-invalid @enderror" 
                                       id="fecha_fin" 
                                       name="fecha_fin" 
                                       value="{{ old('fecha_fin', optional(\Carbon\Carbon::parse($tarea->fecha_fin))->format('Y-m-d')) }}"
                                       required>
                                @error('fecha_fin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Contenedor de Evidencia -->
                        <div id="evidencia-container" class="{{ old('estado_tarea', $tarea->estado_tarea) == $estadoFinalizada ? '' : 'd-none' }}">
                            <!-- Evidencia -->
                            <div class="col-12 mb-3">
                                <label class="form-label" for="evidencia">Evidencia</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-file-upload"></i>
                                    </span>
                                    <input type="file" 
                                           class="form-control @error('evidencia') is-invalid @enderror" 
                                           id="evidencia" 
                                           name="evidencia"
                                           accept="image/*,.pdf"
                                           {{ old('estado_tarea', $tarea->estado_tarea) == $estadoFinalizada ? 'required' : '' }}>
                                    @error('evidencia')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <small class="text-muted">Formatos permitidos: JPEG, PNG, JPG, PDF. Máximo 2MB.</small>
                            </div>

                            <!-- Comprobación -->
                            <div class="col-12 mb-3">
                                <label class="form-label" for="comprobacion">Comprobación</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-check-circle"></i>
                                    </span>
                                    <textarea class="form-control @error('comprobacion') is-invalid @enderror" 
                                              id="comprobacion" 
                                              name="comprobacion" 
                                              rows="3"
                                              {{ old('estado_tarea', $tarea->estado_tarea) == $estadoFinalizada ? 'required' : '' }}>{{ old('comprobacion', $tarea->comprobacion) }}</textarea>
                                    @error('comprobacion')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('tarea.show', $tarea) }}" class="btn btn-light">
                            <i class="fas fa-times me-2"></i>Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Información Adicional -->
    <div class="col-12 col-lg-4">
        <div class="card dashboard-card">
            <div class="card-header">
                <h4 class="card-title"><i class="fas fa-info-circle me-2"></i>Estado Actual</h4>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <span class="text-muted">Estado:</span>
                        <span class="badge bg-{{ $tarea->estado->nombre === 'FINALIZADA' ? 'success' : 
                            ($tarea->estado->nombre === 'EN PROCESO' ? 'primary' : 
                            ($tarea->estaAtrasada() ? 'danger' : 'warning')) }} float-end">
                            {{ $tarea->getEstadoFormateado() }}
                        </span>
                    </li>
                    <li class="list-group-item">
                        <span class="text-muted">Días Restantes:</span>
                        <span class="badge bg-{{ $tarea->getDiasRestantes() < 0 ? 'danger' : 
                            ($tarea->getDiasRestantes() <= 2 ? 'warning' : 'success') }} float-end">
                            {{ $tarea->getDiasRestantes() }} días
                        </span>
                    </li>
                    <li class="list-group-item">
                        <span class="text-muted">Prioridad:</span>
                        <span class="badge bg-{{ $tarea->getPrioridad() === 'Alta' ? 'danger' : 
                            ($tarea->getPrioridad() === 'Media' ? 'warning' : 'info') }} float-end">
                            {{ $tarea->getPrioridad() }}
                        </span>
                    </li>
                </ul>

                @if($tarea->comprobacion)
                <div class="mt-3">
                    <h6 class="text-muted">Evidencia Actual:</h6>
                    <a href="{{ route('tarea.evidencia', $tarea) }}" 
                       class="btn btn-sm btn-info w-100" 
                       target="_blank">
                        <i class="fas fa-file-alt me-2"></i>Ver Evidencia
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Select2 para mejores selects
        $('#proyecto_no_proyecto, #empleado_no, #estado_tarea').select2({
            theme: 'bootstrap-5',
            width: '100%'
        });

        // Validación del formulario
        (function () {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms)
                .forEach(function (form) {
                    form.addEventListener('submit', function (event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }
                        form.classList.add('was-validated')
                    }, false)
                })
        })()

        // Mostrar campos de evidencia si se selecciona estado "FINALIZADA"
        const estadoFinalizada = $('#estado_tarea').data('estado-finalizada');
        
        function toggleEvidenciaFields() {
            const evidenciaContainer = $('#evidencia-container');
            const evidenciaInput = $('#evidencia');
            const comprobacionInput = $('#comprobacion');
            const isEstadoFinalizada = $('#estado_tarea').val() == estadoFinalizada;
            
            evidenciaContainer.toggleClass('d-none',
            !isEstadoFinalizada);
            evidenciaInput.prop('required', isEstadoFinalizada);
            comprobacionInput.prop('required', isEstadoFinalizada);
        }

        // Event listener para cambios en el estado
        $('#estado_tarea').change(toggleEvidenciaFields);
        
        // Verificar estado inicial
        toggleEvidenciaFields();
        
        // Validación de fechas
        $('#fecha_inicio, #fecha_fin').change(function() {
            const fechaInicio = $('#fecha_inicio').val();
            const fechaFin = $('#fecha_fin').val();
            
            if (fechaInicio && fechaFin) {
                if (fechaFin < fechaInicio) {
                    $('#fecha_fin')[0].setCustomValidity('La fecha de finalización debe ser posterior a la fecha de inicio');
                } else {
                    $('#fecha_fin')[0].setCustomValidity('');
                }
            }
        });

        // Validación de archivo
        $('#evidencia').change(function() {
            const file = this.files[0];
            const fileTypes = ['image/jpeg', 'image/png', 'application/pdf'];
            const maxSize = 2 * 1024 * 1024; // 2MB

            if (file) {
                if (!fileTypes.includes(file.type)) {
                    this.setCustomValidity('Por favor, sube un archivo en formato JPEG, PNG o PDF');
                } else if (file.size > maxSize) {
                    this.setCustomValidity('El archivo no debe superar los 2MB');
                } else {
                    this.setCustomValidity('');
                }
            }
        });
    });
</script>
@endpush

@endsection