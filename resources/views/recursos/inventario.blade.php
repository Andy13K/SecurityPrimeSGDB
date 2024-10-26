@extends('layouts.app')

@section('title', 'Inventario de Recursos')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">Inventario de Recursos</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('recurso.index') }}">Recursos</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Inventario</li>
                </ol>
            </nav>
        </div>
        <div class="btn-group">
            <button type="button" class="btn btn-primary" onclick="window.print()">
                <i class="fas fa-print me-2"></i>Imprimir
            </button>
            <button class="btn btn-success" id="exportExcel">
                <i class="fas fa-file-excel me-2"></i>Exportar Excel
            </button>
        </div>
    </div>
</div>

<div class="row">
    <!-- Resumen -->
    <div class="col-12 mb-4">
        <div class="row g-3">
            <div class="col-sm-6 col-xl-3">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-primary bg-opacity-10 rounded p-3">
                                    <i class="fas fa-boxes fa-2x text-primary"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">Total Recursos</h6>
                                <h3 class="mb-0">{{ $recursos->count() }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-success bg-opacity-10 rounded p-3">
                                    <i class="fas fa-dollar-sign fa-2x text-success"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">Valor Total</h6>
                                <h3 class="mb-0">${{ number_format($recursos->sum('cantidad_asignada') * $recursos->avg('precio'), 2) }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-info bg-opacity-10 rounded p-3">
                                    <i class="fas fa-project-diagram fa-2x text-info"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">En Proyectos</h6>
                                <h3 class="mb-0">{{ $recursos->sum('cantidad_asignada') }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-warning bg-opacity-10 rounded p-3">
                                    <i class="fas fa-chart-line fa-2x text-warning"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">Margen Promedio</h6>
                                <h3 class="mb-0">{{ number_format($recursos->avg(function($recurso) {
                                    return (($recurso->precio - $recurso->costo) / $recurso->precio) * 100;
                                }), 1) }}%</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Inventario -->
    <div class="col-12">
        <div class="card dashboard-card">
            <div class="card-header">
                <h4 class="card-title"><i class="fas fa-clipboard-list me-2"></i>Detalle de Inventario</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="inventarioTable">
                        <thead>
                            <tr>
                                <th>Recurso</th>
                                <th>Cantidad Asignada</th>
                                <th>Precio Unitario</th>
                                <th>Costo Unitario</th>
                                <th>Valor Total</th>
                                <th>Margen</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recursos as $recurso)
                            <tr>
                                <td>{{ $recurso->nombre }}</td>
                                <td>{{ $recurso->cantidad_asignada ?? 0 }}</td>
                                <td>${{ number_format($recurso->precio, 2) }}</td>
                                <td>${{ number_format($recurso->costo, 2) }}</td>
                                <td>${{ number_format(($recurso->cantidad_asignada ?? 0) * $recurso->precio, 2) }}</td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar {{ (($recurso->precio - $recurso->costo) / $recurso->precio) * 100 > 30 ? 'bg-success' : 'bg-warning' }}" 
                                             role="progressbar" 
                                             style="width: {{ (($recurso->precio - $recurso->costo) / $recurso->precio) * 100 }}%">
                                            {{ number_format((($recurso->precio - $recurso->costo) / $recurso->precio) * 100, 1) }}%
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('recurso.show', $recurso) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
@media print {
    .btn-group, .nav, .card-header {
        display: none !important;
    }
    .card {
        border: none !important;
    }
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
<script>
$(document).ready(function() {
    // Inicializar DataTable
    var table = $('#inventarioTable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
        },
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        order: [[4, 'desc']]
    });

    // Exportar a Excel
    $('#exportExcel').click(function() {
        const wb = XLSX.utils.table_to_book(document.getElementById('inventarioTable'), {sheet: "Inventario"});
        XLSX.writeFile(wb, 'Inventario_Recursos.xlsx');
    });
});
</script>
@endpush

@endsection