@extends('layouts.app')

@section('title', 'Editar Equipo')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Editar Equipo de Trabajo</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('equipo_trabajo.index') }}">Equipos</a></li>
                <li class="breadcrumb-item"><a href="{{ route('equipo_trabajo.show', $equipo_trabajo) }}">{{ $equipo_trabajo->nombre }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Editar</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-12 col-lg-8">
        <div class="card dashboard-card">
            <div class="card-header">
                <h4 class="card-title"><i class="fas fa-edit me-2"></i>Editar Equipo</h4>
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

                <form action="{{ route('equipo_trabajo.update', $equipo_trabajo) }}" method="POST" class="needs-validation" novalidate>
                    @csrf
                    @method('PUT')
                    
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
                                       value="{{ old('nombre', $equipo_trabajo->nombre) }}"
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
                                <input type="text" 
                                       class="form-control @error('supervisor') is-invalid @enderror" 
                                       id="supervisor" 
                                       name="supervisor" 
                                       value="{{ old('supervisor', $equipo_trabajo->supervisor) }}"
                                       required>
                                @error('supervisor')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('equipo_trabajo.show', $equipo_trabajo) }}" class="btn btn-light">
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
</div>

@push('scripts')
<script>
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