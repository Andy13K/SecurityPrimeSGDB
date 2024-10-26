@extends('layouts.app')

@section('title', 'Editar Factura')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Editar Factura</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('factura.index') }}">Facturas</a></li>
                <li class="breadcrumb-item"><a href="{{ route('factura.show', $factura) }}">Factura #{{ $factura->getNumeroFacturaFormateado() }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Editar</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-12 col-lg-8">
        <div class="card dashboard-card">
            <div class="card-header">
                <h4 class="card-title"><i class="fas fa-edit me-2"></i>Editar Factura</h4>
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

                <form action="{{ route('factura.update', $factura) }}" method="POST" class="needs-validation" novalidate>
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <!-- Información del Proyecto (No editable) -->
                        <div class="col-12 mb-3">
                            <label class="form-label">Proyecto</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-project-diagram"></i>
                                </span>
                                <input type="text" 
                                       class="form-control" 
                                       value="{{ $factura->proyecto->nombre }}"
                                       readonly>
                            </div>
                            <small class="text-muted">Cliente: {{ $factura->proyecto->cliente->nombre }}</small>
                        </div>

                        <!-- NIT -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="nit">NIT</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-id-card"></i>
                                </span>
                                <input type="text" 
                                       class="form-control @error('nit') is-invalid @enderror" 
                                       id="nit" 
                                       name="nit" 
                                       value="{{ old('nit', $factura->nit) }}"
                                       required>
                                @error('nit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Total -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="total">Total</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-dollar-sign"></i>
                                </span>
                                <input type="number" 
                                       class="form-control @error('total') is-invalid @enderror" 
                                       id="total" 
                                       name="total" 
                                       value="{{ old('total', $factura->total) }}"
                                       step="0.01"
                                       min="0"
                                       required>
                                @error('total')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('factura.show', $factura) }}" class="btn btn-light">
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

    <!-- Panel de Detalles -->
    <div class="col-12 col-lg-4">
        <div class="card dashboard-card">
            <div class="card-header">
                <h4 class="card-title"><i class="fas fa-info-circle me-2"></i>Información de la Factura</h4>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <span class="text-muted">Número de Factura:</span>
                        <span class="float-end fw-bold">{{ $factura->getNumeroFacturaFormateado() }}</span>
                    </li>
                    <li class="list-group-item">
                        <span class="text-muted">Fecha de Emisión:</span>
                        <td>{{ $factura->fecha_emision ? \Carbon\Carbon::parse($factura->fecha_emision)->format('d/m/Y') : 'N/A' }}</td>
                    </li>
                    <li class="list-group-item">
                        <span class="text-muted">Estado:</span>
                        <span class="badge bg-{{ $factura->estaVencida() ? 'danger' : 'success' }} float-end">
                            {{ $factura->getEstado() }}
                        </span>
                    </li>
                    <li class="list-group-item">
                        <span class="text-muted">Subtotal Recursos:</span>
                        <span class="float-end">${{ number_format($factura->subtotal_recursos, 2) }}</span>
                    </li>
                    <li class="list-group-item">
                        <span class="text-muted">Mano de Obra:</span>
                        <span class="float-end">${{ number_format($factura->mano_obra, 2) }}</span>
                    </li>
                    <li class="list-group-item">
                        <span class="text-muted">Impuestos (12%):</span>
                        <span class="float-end">${{ number_format($factura->calcularImpuestos(), 2) }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
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

@endsection