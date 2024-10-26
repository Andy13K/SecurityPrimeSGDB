@extends('layouts.app')

@section('title', 'Editar Recurso')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Editar Recurso</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('recurso.index') }}">Recursos</a></li>
                <li class="breadcrumb-item"><a href="{{ route('recurso.show', $recurso) }}">{{ $recurso->nombre }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Editar</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-12 col-lg-8">
        <div class="card dashboard-card">
            <div class="card-header">
                <h4 class="card-title"><i class="fas fa-edit me-2"></i>Editar Recurso</h4>
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

                <form action="{{ route('recurso.update', $recurso) }}" method="POST" class="needs-validation" novalidate>
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label class="form-label" for="nombre">Nombre del Recurso</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-box"></i>
                                </span>
                                <input type="text" 
                                       class="form-control @error('nombre') is-invalid @enderror" 
                                       id="nombre" 
                                       name="nombre" 
                                       value="{{ old('nombre', $recurso->nombre) }}"
                                       required>
                                @error('nombre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="precio">Precio de Venta</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-dollar-sign"></i>
                                </span>
                                <input type="number" 
                                       class="form-control @error('precio') is-invalid @enderror" 
                                       id="precio" 
                                       name="precio" 
                                       value="{{ old('precio', $recurso->precio) }}"
                                       step="0.01"
                                       min="0"
                                       required>
                                @error('precio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="costo">Costo</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-dollar-sign"></i>
                                </span>
                                <input type="number" 
                                       class="form-control @error('costo') is-invalid @enderror" 
                                       id="costo" 
                                       name="costo" 
                                       value="{{ old('costo', $recurso->costo) }}"
                                       step="0.01"
                                       min="0"
                                       required>
                                @error('costo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('recurso.show', $recurso) }}" class="btn btn-light">
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

    <div class="col-12 col-lg-4">
        <div class="card dashboard-card">
            <div class="card-header">
                <h4 class="card-title"><i class="fas fa-chart-line me-2"></i>Rendimiento Actual</h4>
            </div>
            <div class="card-body">
                <div id="margen-preview" class="mb-4">
                    <h6>Margen Actual</h6>
                    <div class="progress mb-2" style="height: 20px;">
                        <div class="progress-bar {{ (($recurso->precio - $recurso->costo) / $recurso->precio) * 100 > 30 ? 'bg-success' : 'bg-warning' }}" 
                             role="progressbar" 
                             style="width: {{ (($recurso->precio - $recurso->costo) / $recurso->precio) * 100 }}%">
                            {{ number_format((($recurso->precio - $recurso->costo) / $recurso->precio) * 100, 1) }}%
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-sm">
                        <tr>
                            <td class="text-muted">Proyectos Activos:</td>
                            <td class="text-end">
                                <span class="badge bg-primary">{{ $recurso->proyectos()->count() }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Total Asignado:</td>
                            <td class="text-end">
                                <span class="badge bg-info">
                                    {{ $recurso->proyectos()->sum('cantidad_asignada') }} unidades
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
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

    // Cálculo del margen en tiempo real
    function actualizarMargen() {
        const precio = parseFloat($('#precio').val()) || 0;
        const costo = parseFloat($('#costo').val()) || 0;
        
        if (precio > 0 && costo > 0) {
            const margen = ((precio - costo) / precio) * 100;
            const progressBar = $('#margen-preview .progress-bar');
            
            progressBar.css('width', margen + '%')
                      .text(margen.toFixed(1) + '%')
                      .removeClass('bg-danger bg-warning bg-success')
                      .addClass(margen < 20 ? 'bg-danger' : 
                              margen < 30 ? 'bg-warning' : 'bg-success');
        }
    }

    $('#precio, #costo').on('input', actualizarMargen);
</script>
@endpush

@endsection