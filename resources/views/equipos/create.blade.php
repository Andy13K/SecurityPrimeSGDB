@extends('layouts.app')

@section('title', 'Crear Equipo')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Crear Equipo de Trabajo</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('equipo_trabajo.index') }}">Equipos</a></li>
                <li class="breadcrumb-item active" aria-current="page">Crear</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-12 col-lg-8">
        <div class="card dashboard-card">
            <div class="card-header">
                <h4 class="card-title"><i class="fas fa-plus me-2"></i>Nuevo Equipo</h4>
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

                <form action="{{ route('equipo_trabajo.store') }}" method="POST" class="needs-validation" novalidate>
                    @csrf
                    
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label class="form-label" for="nombre">Nombre del Equipo</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-users"></i>
                                </span>
                                <input type="text" 
                                       class="form-control @error('nombre') is-invalid @enderror" 
                                       id="nombre" 
                                       name="nombre" 
                                       value="{{ old('nombre') }}"
                                       required>
                                @error('nombre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label" for="supervisor">Supervisor</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-user-tie"></i>
                                </span>
                                <select class="form-select @error('supervisor') is-invalid @enderror" 
                                        id="supervisor" 
                                        name="supervisor" 
                                        required>
                                    <option value="">Seleccione un supervisor</option>
                                    @foreach($empleados as $empleado)
                                        <option value="{{ $empleado->nombre }}" 
                                                {{ old('supervisor') == $empleado->nombre ? 'selected' : '' }}>
                                            {{ $empleado->nombre }} - 
                                            {{ $empleado->cargo->nombre ?? 'Sin cargo' }}
                                            ({{ $empleado->equipo->nombre ?? 'Sin equipo' }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('supervisor')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-text">
                                Seleccione el empleado que será supervisor del equipo
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('equipo_trabajo.index') }}" class="btn btn-light">
                            <i class="fas fa-times me-2"></i>Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <div class="card dashboard-card">
            <div class="card-header">
                <h4 class="card-title"><i class="fas fa-info-circle me-2"></i>Información</h4>
            </div>
            <div class="card-body">
                <p class="text-muted">
                    Al crear un nuevo equipo de trabajo, podrás:
                </p>
                <ul class="text-muted">
                    <li>Asignar empleados al equipo</li>
                    <li>Gestionar proyectos del equipo</li>
                    <li>Monitorear el rendimiento</li>
                    <li>Ver estadísticas y métricas</li>
                </ul>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    // Inicializar Select2 para el selector de supervisor
    $(document).ready(function() {
        $('#supervisor').select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: 'Seleccione un supervisor',
            allowClear: true,
            language: {
                noResults: function() {
                    return "No se encontraron resultados";
                },
                searching: function() {
                    return "Buscando...";
                }
            }
        });
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
</script>
@endpush

@endsection