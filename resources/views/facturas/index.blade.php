@extends('layouts.app')

@section('title', 'Facturas')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">Facturas</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Facturas</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('factura.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Nueva Factura
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

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card dashboard-card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="facturasTable">
                <thead>
                    <tr>
                        <th>No. Factura</th>
                        <th>Proyecto</th>
                        <th>Cliente</th>
                        <th>NIT</th>
                        <th>Fecha Emisión</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($facturas as $factura)
                    <tr>
                        <td>{{ $factura->getNumeroFacturaFormateado() }}</td>
                        <td>
                            <a href="{{ route('proyecto.show', $factura->proyecto) }}">
                                {{ $factura->proyecto->nombre }}
                            </a>
                        </td>
                        <td>{{ $factura->proyecto->cliente->nombre }}</td>
                        <td>{{ $factura->nit }}</td>
                        <td>{{ $factura->fecha_emision ? \Carbon\Carbon::parse($factura->fecha_emision)->format('d/m/Y') : 'N/A' }}</td>
                        <td>${{ number_format($factura->total, 2) }}</td>
                        <td>
                            <span class="badge bg-{{ $factura->estaVencida() ? 'danger' : 'success' }}">
                                {{ $factura->getEstado() }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('factura.show', $factura) }}" 
                                   class="btn btn-sm btn-info" 
                                   title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('factura.preview', $factura) }}" 
                                   class="btn btn-sm btn-secondary" 
                                   title="Vista previa"
                                   target="_blank">
                                    <i class="fas fa-file-alt"></i>
                                </a>
                                <a href="{{ route('factura.generatePDF', $factura) }}" 
                                   class="btn btn-sm btn-primary" 
                                   title="Descargar PDF">
                                    <i class="fas fa-download"></i>
                                </a>
                                <a href="{{ route('factura.edit', $factura) }}" 
                                   class="btn btn-sm btn-warning" 
                                   title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('factura.destroy', $factura) }}" 
                                      method="POST" 
                                      class="d-inline"
                                      onsubmit="return confirm('¿Está seguro de eliminar esta factura?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="btn btn-sm btn-danger" 
                                            title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <i class="fas fa-file-invoice fa-3x text-muted mb-3"></i>
                            <h4 class="text-muted">No hay facturas registradas</h4>
                            <a href="{{ route('factura.create') }}" class="btn btn-primary mt-3">
                                <i class="fas fa-plus me-2"></i>Crear Factura
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('#facturasTable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
        },
        order: [[4, 'desc']], // Ordenar por fecha de emisión descendente
        responsive: true,
        columns: [
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            { orderable: false } // Columna de acciones no ordenable
        ]
    });
});
</script>
@endpush

@endsection