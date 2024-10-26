@extends('layouts.app')

@section('title', 'Crear Factura')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Crear Factura</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('factura.index') }}">Facturas</a></li>
                <li class="breadcrumb-item active" aria-current="page">Crear</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-12 col-lg-8">
        <div class="card dashboard-card">
            <div class="card-header">
                <h4 class="card-title"><i class="fas fa-file-invoice me-2"></i>Nueva Factura</h4>
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

                <form action="{{ route('factura.store') }}" method="POST" class="needs-validation" novalidate>
                    @csrf
                    
                    <div class="row">
                        <!-- Proyecto -->
                        <div class="col-12 mb-3">
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
                                                data-cliente="{{ $proyecto->cliente->nombre }}"
                                                data-nit="{{ $proyecto->cliente->nit }}">
                                            {{ $proyecto->nombre }}
                                            ({{ $proyecto->cliente->nombre }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('proyecto_no_proyecto')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
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
                                       value="{{ old('nit') }}"
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
                                       value="{{ old('total') }}"
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
                        <a href="{{ route('factura.index') }}" class="btn btn-light">
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

    <!-- Panel de Detalles -->
    <div class="col-12 col-lg-4">
        <div class="card dashboard-card">
            <div class="card-header">
                <h4 class="card-title"><i class="fas fa-calculator me-2"></i>Detalles del Proyecto</h4>
            </div>
            <div class="card-body">
                <div id="detalles-container" class="d-none">
                    <div class="mb-4">
                        <h6>Cliente</h6>
                        <p id="cliente-nombre" class="mb-0 fw-bold">-</p>
                        <small id="cliente-nit" class="text-muted">-</small>
                    </div>

                    <div class="mb-4">
                        <h6>Recursos Asignados</h6>
                        <div id="recursos-list" class="list-group list-group-flush">
                            <!-- Los recursos se cargarán dinámicamente -->
                        </div>
                    </div>

                    <div class="mt-3">
                        <table class="table table-sm">
                            <tr>
                                <td class="text-muted">Subtotal Recursos:</td>
                                <td class="text-end" id="subtotal-recursos">$0.00</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Mano de Obra:</td>
                                <td class="text-end" id="mano-obra">$0.00</td>
                            </tr>
                            <tr class="fw-bold">
                                <td>Total:</td>
                                <td class="text-end" id="total-calculado">$0.00</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div id="no-proyecto" class="text-center py-4">
                    <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Seleccione un proyecto para ver los detalles</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Inicializar Select2
    $('#proyecto_no_proyecto').select2({
        theme: 'bootstrap-5',
        width: '100%',
        placeholder: 'Seleccione un proyecto'
    });

    // Cuando se selecciona un proyecto
    $('#proyecto_no_proyecto').change(function() {
        const proyecto = $(this).val();
        const clienteNombre = $('option:selected', this).data('cliente');
        const clienteNit = $('option:selected', this).data('nit');

        if (proyecto) {
            // Mostrar spinner de carga
            $('#detalles-container').addClass('d-none');
            $('#no-proyecto').html('<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Cargando...</span></div>');

            // Cargar los totales
            $.get(`/factura/calcular-totales/${proyecto}`, function(data) {
                $('#detalles-container').removeClass('d-none');
                $('#no-proyecto').addClass('d-none');

                // Actualizar información del cliente
                $('#cliente-nombre').text(clienteNombre);
                $('#cliente-nit').text(`NIT: ${clienteNit}`);
                $('#nit').val(clienteNit);

                // Actualizar totales
                $('#subtotal-recursos').text(`$${parseFloat(data.subtotal_recursos).toFixed(2)}`);
                $('#mano-obra').text(`$${parseFloat(data.mano_obra).toFixed(2)}`);
                $('#total-calculado').text(`$${parseFloat(data.total).toFixed(2)}`);
                $('#total').val(data.total);
            });
        } else {
            $('#detalles-container').addClass('d-none');
            $('#no-proyecto').removeClass('d-none')
                           .html('<i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i><p class="text-muted">Seleccione un proyecto para ver los detalles</p>');
        }
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

@endsection