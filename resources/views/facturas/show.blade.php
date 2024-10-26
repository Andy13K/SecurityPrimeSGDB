@extends('layouts.app')

@section('title', 'Detalle de Factura')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">Factura #{{ $factura->getNumeroFacturaFormateado() }}</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('factura.index') }}">Facturas</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Factura #{{ $factura->getNumeroFacturaFormateado() }}</li>
                </ol>
            </nav>
        </div>
        <div class="btn-group">
            <a href="{{ route('factura.edit', $factura) }}" class="btn btn-warning">
                <i class="fas fa-edit me-2"></i>Editar
            </a>
            <a href="{{ route('factura.generatePDF', $factura) }}" class="btn btn-primary">
                <i class="fas fa-download me-2"></i>Descargar PDF
            </a>
            <a href="{{ route('factura.preview', $factura) }}" class="btn btn-info" target="_blank">
                <i class="fas fa-eye me-2"></i>Vista Previa
            </a>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row">
    <!-- Información Principal -->
    <div class="col-12 col-lg-8">
        <div class="card dashboard-card mb-4">
            <div class="card-body">
                <div class="row">
                    <!-- Información del Cliente -->
                    <div class="col-sm-6 mb-4">
                        <h6 class="text-muted mb-3">Cliente</h6>
                        <h5>{{ $factura->proyecto->cliente->nombre }}</h5>
                        <p class="mb-0">NIT: {{ $factura->nit }}</p>
                        <p class="mb-0">{{ $factura->proyecto->cliente->direccion ?? 'Sin dirección' }}</p>
                        <p class="mb-0">{{ $factura->proyecto->cliente->telefono ?? 'Sin teléfono' }}</p>
                    </div>

                    <!-- Información de la Factura -->
                    <div class="col-sm-6 mb-4">
                        <h6 class="text-muted mb-3">Detalles de Factura</h6>
                        <p class="mb-1">
                            <span class="text-muted">Fecha de Emisión:</span><br>
                            <td>{{ $factura->fecha_emision ? \Carbon\Carbon::parse($factura->fecha_emision)->format('d/m/Y') : 'N/A' }}</td>
                        </p>
                        <p class="mb-1">
                            <span class="text-muted">Proyecto:</span><br>
                            {{ $factura->proyecto->nombre }}
                        </p>
                        <p class="mb-0">
                            <span class="text-muted">Estado:</span><br>
                            <span class="badge bg-{{ $factura->estaVencida() ? 'danger' : 'success' }}">
                                {{ $factura->getEstado() }}
                            </span>
                        </p>
                    </div>
                </div>

                <!-- Detalle de Recursos -->
                <div class="table-responsive mt-4">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Recurso</th>
                                <th class="text-center">Cantidad</th>
                                <th class="text-end">Precio Unitario</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($detalles['recursos'] as $recurso)
                            <tr>
                                <td>{{ $recurso['nombre'] }}</td>
                                <td class="text-center">{{ $recurso['cantidad'] }}</td>
                                <td class="text-end">${{ number_format($recurso['precio_unitario'], 2) }}</td>
                                <td class="text-end">${{ number_format($recurso['subtotal'], 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="3" class="text-end"><strong>Subtotal Recursos:</strong></td>
                                <td class="text-end">${{ number_format($detalles['subtotal_recursos'], 2) }}</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Mano de Obra:</strong></td>
                                <td class="text-end">${{ number_format($detalles['mano_obra'], 2) }}</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end"><strong>IVA (12%):</strong></td>
                                <td class="text-end">${{ number_format($factura->calcularImpuestos(), 2) }}</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                <td class="text-end"><strong>${{ number_format($detalles['total'], 2) }}</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Panel Lateral -->
    <div class="col-12 col-lg-4">
        <!-- Información del Proyecto -->
        <div class="card dashboard-card mb-4">
            <div class="card-header">
                <h4 class="card-title"><i class="fas fa-project-diagram me-2"></i>Proyecto</h4>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <span class="text-muted">Nombre:</span>
                        <span class="float-end fw-bold">{{ $factura->proyecto->nombre }}</span>
                    </li>
                    <li class="list-group-item">
                        <span class="text-muted">Estado:</span>
                        <span class="float-end">{{ $factura->proyecto->estado->first()->nombre ?? 'Sin estado' }}</span>
                    </li>
                    <li class="list-group-item">
                        <span class="text-muted">Fecha Inicio:</span>
                        <span class="float-end">{{ optional($factura->proyecto->fecha_inicio)->format('d/m/Y') }}</span>
                    </li>
                    <li class="list-group-item">
                        <span class="text-muted">Fecha Fin:</span>
                        <span class="float-end">{{ optional($factura->proyecto->fecha_fin)->format('d/m/Y') }}</span>
                    </li>
                </ul>
                <div class="mt-3">
                    <a href="{{ route('proyecto.show', $factura->proyecto) }}" class="btn btn-info btn-sm w-100">
                        <i class="fas fa-external-link-alt me-2"></i>Ver Proyecto
                    </a>
                </div>
            </div>
        </div>

        <!-- Acciones -->
        <div class="card dashboard-card">
            <div class="card-header">
                <h4 class="card-title"><i class="fas fa-cog me-2"></i>Acciones</h4>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('factura.edit', $factura) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>Editar Factura
                    </a>
                    <a href="{{ route('factura.generatePDF', $factura) }}" class="btn btn-primary">
                        <i class="fas fa-download me-2"></i>Descargar PDF
                    </a>
                    <a href="{{ route('factura.preview', $factura) }}" class="btn btn-info" target="_blank">
                        <i class="fas fa-eye me-2"></i>Vista Previa
                    </a>
                    <form action="{{ route('factura.destroy', $factura) }}" 
                          method="POST" 
                          onsubmit="return confirm('¿Está seguro de eliminar esta factura?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="fas fa-trash me-2"></i>Eliminar Factura
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection