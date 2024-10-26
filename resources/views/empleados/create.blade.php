@extends('layouts.app')

@section('title', 'Crear Empleado')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Crear Empleado</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('empleado.index') }}">Empleados</a></li>
                <li class="breadcrumb-item active" aria-current="page">Crear</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-12 col-lg-8">
        <div class="card dashboard-card">
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

                <form action="{{ route('empleado.store') }}" method="POST" class="needs-validation" novalidate>
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="nombre">Nombre</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-user"></i>
                                </span>
                                <input type="text" 
                                       class="form-control @error('nombre') is-invalid @enderror" 
                                       id="nombre" 
                                       name="nombre" 
                                       value="{{ old('nombre') }}" 
                                       placeholder="Ingrese el nombre completo"
                                       required>
                                @error('nombre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="telefono">Teléfono</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-phone"></i>
                                </span>
                                <input type="text" 
                                       class="form-control @error('telefono') is-invalid @enderror" 
                                       id="telefono" 
                                       name="telefono" 
                                       value="{{ old('telefono') }}" 
                                       placeholder="8 dígitos"
                                       maxlength="8"
                                       required>
                                @error('telefono')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="text-muted">Formato: XXXXXXXX (8 dígitos)</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="correo">Correo Electrónico</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-envelope"></i>
                                </span>
                                <input type="email" 
                                       class="form-control @error('correo') is-invalid @enderror" 
                                       id="correo" 
                                       name="correo" 
                                       value="{{ old('correo') }}" 
                                       placeholder="ejemplo@dominio.com"
                                       required>
                                @error('correo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="cargo_no_cargo">Cargo</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-briefcase"></i>
                                </span>
                                <select class="form-select @error('cargo_no_cargo') is-invalid @enderror" 
                                        id="cargo_no_cargo" 
                                        name="cargo_no_cargo" 
                                        required>
                                    <option value="">Seleccione un cargo</option>
                                    @foreach($cargos as $cargo)
                                        <option value="{{ $cargo->no_cargo }}" 
                                                {{ old('cargo_no_cargo') == $cargo->no_cargo ? 'selected' : '' }}>
                                            {{ $cargo->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('cargo_no_cargo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="no_equipo">Equipo de Trabajo</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-users"></i>
                                </span>
                                <select class="form-select @error('no_equipo') is-invalid @enderror" 
                                        id="no_equipo" 
                                        name="no_equipo" 
                                        required>
                                    <option value="">Seleccione un equipo</option>
                                    @foreach($equipos as $equipo)
                                        <option value="{{ $equipo->no_equipo }}" 
                                                {{ old('no_equipo') == $equipo->no_equipo ? 'selected' : '' }}>
                                            {{ $equipo->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('no_equipo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="no_especializacion">Especialización</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-code-branch"></i>
                                </span>
                                <select class="form-select @error('no_especializacion') is-invalid @enderror" 
                                        id="no_especializacion" 
                                        name="no_especializacion" 
                                        required>
                                    <option value="">Seleccione una especialización</option>
                                    @foreach($especializaciones as $especializacion)
                                        <option value="{{ $especializacion->no_especializacion }}" 
                                                {{ old('no_especializacion') == $especializacion->no_especializacion ? 'selected' : '' }}>
                                            {{ $especializacion->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('no_especializacion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12 mb-4">
                            <label class="form-label" for="direccion">Dirección</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-map-marker-alt"></i>
                                </span>
                                <textarea class="form-control @error('direccion') is-invalid @enderror" 
                                          id="direccion" 
                                          name="direccion" 
                                          rows="3" 
                                          placeholder="Ingrese la dirección completa"
                                          required>{{ old('direccion') }}</textarea>
                                @error('direccion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('empleado.index') }}" class="btn btn-light">
                            <i class="fas fa-times me-2"></i>Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Guardar Empleado
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <div class="card dashboard-card">
            <div class="card-header">
                <h4 class="card-title">Información Adicional</h4>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <h5 class="alert-heading"><i class="fas fa-info-circle me-2"></i>Instrucciones</h5>
                    <p class="mb-0">Complete todos los campos requeridos para registrar un nuevo empleado. Asegúrese de:</p>
                    <ul class="mt-2 mb-0">
                        <li>Ingresar un número telefónico de 8 dígitos</li>
                        <li>Usar un correo electrónico válido</li>
                        <li>Seleccionar cargo, equipo y especialización</li>
                        <li>Proporcionar una dirección completa</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Inicializar validación del formulario
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

        // Máscara para el teléfono
        $('#telefono').on('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    });
</script>
@endpush

@endsection