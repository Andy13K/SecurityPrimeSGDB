@extends('layouts.app')

@section('title', 'Crear Recurso')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Crear Recurso</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('recurso.index') }}">Recursos</a></li>
                <li class="breadcrumb-item active" aria-current="page">Crear</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-12 col-lg-8">
        <div class="card dashboard-card">
            <div class="card-header">
                <h4 class="card-title"><i class="fas fa-plus me-2"></i>Nuevo Recurso</h4>
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

                <form action="{{ route('recurso.store') }}" method="POST" class="needs-validation" novalidate>
                    @csrf
                    
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
                                       value="{{ old('nombre') }}"
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
                                       value="{{ old('precio') }}"
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
                                       value="{{ old('costo') }}"
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
                        <a href="{{ route('recurso.index') }}" class="btn btn-light">
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
                <div id="margen-preview" class="mb-4">
                    <h6>Previsualización del Margen</h6>
                    <div class="d-flex align-items-center mb-2">
                        <div class="progress flex-grow-1 me-2" style="height: 20px;">
                            <div class="progress-bar" role="progressbar" style="width: 0%">0%</div>
                        </div>
                    </div>
                    <small class="text-muted">Margen de ganancia calculado</small>
                </div>

                <h6>Recomendaciones:</h6>
                <ul class="text-muted">
                    <li>Use nombres descriptivos y específicos</li>
                    <li>Incluya marca y modelo si aplica</li>
                    <li>Verifique los precios antes de guardar</li>
                    <li>El costo debe ser menor al precio de venta</li>
                </ul>
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