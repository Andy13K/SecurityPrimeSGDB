@extends('layouts.app')

@section('title', 'Reporte de Uso - ' . $recurso->nombre)

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">Reporte de Uso - {{ $recurso->nombre }}</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('recurso.index') }}">Recursos</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('recurso.show', $recurso) }}">{{ $recurso->nombre }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Reporte de Uso</li>
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
    <!-- Gráfica de Uso Mensual -->
    <div class="col-12 mb-4">
        <div class="card dashboard-card">
            <div class="card-header">
                <h4 class="card-title"><i class="fas fa-chart-line me-2"></i>Uso Mensual</h4>
            </div>
            <div class="card-body">
                <canvas id="usoMensualChart" height="100"></canvas>
            </div>
        </div>
    </div>

    <!-- Detalles del Uso -->
    <div class="col-12">
        <div class="card dashboard-card">
            <div class="card-header">
                <h4 class="card-title"><i class="fas fa-list me-2"></i>Detalle por Mes</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="usoTable">
                        <thead>
                            <tr>
                                <th>Mes</th>
                                <th>Cantidad Utilizada</th>
                                <th>Valor Total</th>
                                <th>% del Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $totalGeneral = $uso_mensual->sum('total');
                            @endphp
                            @foreach($uso_mensual as $uso)
                            <tr>
                                <td>{{ \Carbon\Carbon::createFromFormat('Y-m', $uso->mes)->format('F Y') }}</td>
                                <td>{{ $uso->total }}</td>
                                <td>${{ number_format($uso->total * $recurso->precio, 2) }}</td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-info" 
                                             role="progressbar" 
                                             style="width: {{ ($uso->total / $totalGeneral) * 100 }}%">
                                            {{ number_format(($uso->total / $totalGeneral) * 100, 1) }}%
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="fw-bold">
                                <td>Total</td>
                                <td>{{ $totalGeneral }}</td>
                                <td>${{ number_format($totalGeneral * $recurso->precio, 2) }}</td>
                                <td>100%</td>
                            </tr>
                        </tfoot>
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
<script>
$(document).ready(function() {
    // Datos para la gráfica
    const datos = @json($uso_mensual);
    const ctx = document.getElementById('usoMensualChart').getContext('2d');
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: datos.map(d => moment(d.mes).format('MMM YYYY')),
            datasets: [{
                label: 'Cantidad Utilizada',
                data: datos.map(d => d.total),
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Uso Mensual del Recurso'
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Inicializar DataTable
    $('#usoTable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
        },
        order: [[0, 'desc']]
    });

    // Exportar a Excel
    $('#exportExcel').click(function() {
        const wb = XLSX.utils.table_to_book(document.getElementById('usoTable'), {sheet: "Uso_Mensual"});
        XLSX.writeFile(wb, `Reporte_Uso_${$recurso->nombre}.xlsx`);
    });
});
</script>
@endpush

@endsection