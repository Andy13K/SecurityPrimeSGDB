@extends('layouts.app')

@section('title', 'Crear Nueva Tarea')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Crear Nueva Tarea</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('tarea.index') }}">Tareas</a></li>
                <li class="breadcrumb-item active" aria-current="page">Crear</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-12 col-lg-8">
        <div class="card dashboard-card">
            <div class="card-header">
                <h4 class="card-title"><i class="fas fa-tasks me-2"></i>Información de la Tarea</h4>
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

                <form action="{{ route('tarea.store') }}" method="POST" class="needs-validation" novalidate>
                    @csrf
                    
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
                                                {{ old('proyecto_no_proyecto') == $proyecto->no_proyecto ? 'selected' : '' }}>
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

                        <!-- Empleado Asignado -->
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
                                                {{ old('empleado_no') == $empleado->no_empleado ? 'selected' : '' }}>
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

                        <!-- Descripción -->
                        <div class="col-12 mb-3">
                            <label class="form-label" for="descripcion">Descripción de la Tarea</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-align-left"></i>
                                </span>
                                <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                                          id="descripcion" 
                                          name="descripcion" 
                                          rows="3" 
                                          required>{{ old('descripcion') }}</textarea>
                                @error('descripcion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="text-muted">
                                Describa detalladamente la tarea a realizar
                            </small>
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
                                       value="{{ old('fecha_inicio') }}"
                                       min="{{ date('Y-m-d') }}"
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
                                       value="{{ old('fecha_fin') }}"
                                       min="{{ date('Y-m-d') }}"
                                       required>
                                @error('fecha_fin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('tarea.index') }}" class="btn btn-light">
                            <i class="fas fa-times me-2"></i>Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Crear Tarea
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
                <h4 class="card-title"><i class="fas fa-info-circle me-2"></i>Información</h4>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <h5 class="alert-heading">Instrucciones</h5>
                    <p class="mb-0">Complete todos los campos requeridos para crear una nueva tarea:</p>
                    <ul class="mt-2 mb-0">
                        <li>Seleccione el proyecto al que pertenece la tarea</li>
                        <li>Asigne un empleado responsable</li>
                        <li>Proporcione una descripción clara y detallada</li>
                        <li>Establezca fechas de inicio y fin realistas</li>
                    </ul>
                </div>

                <div class="alert alert-warning mt-3">
                    <h5 class="alert-heading">Importante</h5>
                    <ul class="mb-0">
                        <li>La fecha de inicio debe ser igual o posterior a hoy</li>
                        <li>La fecha de fin debe ser posterior a la fecha de inicio</li>
                        <li>Al crear la tarea, su estado inicial será "CREADA"</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Validación personalizada para fechas
        const fechaInicio = document.getElementById('fecha_inicio');
        const fechaFin = document.getElementById('fecha_fin');

        fechaInicio.addEventListener('change', function() {
            fechaFin.min = this.value;
            if(fechaFin.value && fechaFin.value < this.value) {
                fechaFin.value = this.value;
            }
        });

        // Select2 para mejores selects
        $('#proyecto_no_proyecto, #empleado_no').select2({
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
    });
</script>
@endpush

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endpush

@endsection