@extends('layouts.app')

@section('title', 'Proyectos del Cliente')

@section('content')
<div class="page-header mb-2">
    <div>
        <h1 class="page-title">Proyectos del Cliente: {{ $cliente->nombre }}</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('cliente.index') }}">Clientes</a></li>
                <li class="breadcrumb-item active">Proyectos</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row g-2">
    <!-- Resumen de Proyectos -->
    <div class="col-12 mb-2">
        <div class="card">
            <div class="card-body p-2">
                <div class="row text-center">
                    <div class="col-md-3">
                        <h6 class="text-muted mb-1">Total Proyectos</h6>
                        <h4 class="mb-0">{{ $proyectos->count() }}</h4>
                    </div>
                    <div class="col-md-3">
                        <h6 class="text-muted mb-1">En Proceso</h6>
                        <h4 class="mb-0">{{ $proyectos->filter(function($p) { 
                            return $p->estado->contains('nombre', 'EN PROCESO'); 
                        })->count() }}</h4>
                    </div>
                    <div class="col-md-3">
                        <h6 class="text-muted mb-1">Finalizados</h6>
                        <h4 class="mb-0">{{ $proyectos->filter(function($p) { 
                            return $p->estado->contains('nombre', 'FINALIZADO'); 
                        })->count() }}</h4>
                    </div>
                    <div class="col-md-3">
                        <h6 class="text-muted mb-1">Inversión Total</h6>
                        <h4 class="mb-0">Q{{ number_format($proyectos->sum('mano_obra'), 2) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Proyectos -->
    <div class="col-12">
        <div class="card">
            <div class="card-header py-2 bg-light">
                <h3 class="card-title mb-0">Lista de Proyectos</h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm mb-0">
                        <thead>
                            <tr class="table-light">
                                <th width="5%">#</th>
                                <th width="20%">Nombre</th>
                                <th width="15%">Equipo</th>
                                <th width="10%">Inicio</th>
                                <th width="10%">Fin</th>
                                <th width="10%">Estado</th>
                                <th width="10%">Progreso</th>
                                <th width="10%">Inversión</th>
                                <th width="10%">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($proyectos as $proyecto)
                                <tr>
                                    <td class="text-center">{{ $proyecto->no_proyecto }}</td>
                                    <td>{{ $proyecto->nombre }}</td>
                                    <td>{{ $proyecto->equipo->nombre }}</td>
                                    <td class="text-center">{{ $proyecto->fecha_inicio->format('d/m/Y') }}</td>
                                    <td class="text-center">{{ $proyecto->fecha_fin->format('d/m/Y') }}</td>
                                    <td class="text-center">
                                        @foreach($proyecto->estado as $estado)
                                            <span class="badge bg-{{ $estado->nombre == 'CREADO' ? 'warning' : 
                                                ($estado->nombre == 'EN PROCESO' ? 'primary' : 'success') }}">
                                                {{ $estado->nombre }}
                                            </span>
                                        @endforeach
                                    </td>
                                    <td>
                                        <div class="progress" style="height: 15px;">
                                            <div class="progress-bar bg-success" 
                                                 role="progressbar" 
                                                 style="width: {{ $proyecto->calcularPorcentajeCompletado() }}%"
                                                 title="{{ number_format($proyecto->calcularPorcentajeCompletado(), 1) }}%">
                                                {{ number_format($proyecto->calcularPorcentajeCompletado(), 0) }}%
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        Q{{ number_format($proyecto->calcularCostoTotal(), 2) }}
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('proyecto.show', $proyecto) }}" 
                                           class="btn btn-sm btn-info" 
                                           title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">No hay proyectos registrados para este cliente</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="7" class="text-end"><strong>Total Inversión:</strong></td>
                                <td class="text-end"><strong>Q{{ number_format($proyectos->sum(function($p) { 
                                    return $p->calcularCostoTotal(); 
                                }), 2) }}</strong></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos (Opcional) -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header py-2 bg-light">
                <h3 class="card-title mb-0">Estado de Proyectos</h3>
            </div>
            <div class="card-body">
                <canvas id="estadoProyectos" height="200"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header py-2 bg-light">
                <h3 class="card-title mb-0">Inversión por Mes</h3>
            </div>
            <div class="card-body">
                <canvas id="inversionMensual" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function() {
        // Inicializar DataTable
        $('.table').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json'
            },
            pageLength: 10,
            responsive: true,
            order: [[0, 'desc']],
        });

        // Gráfico de Estados
        new Chart(document.getElementById('estadoProyectos'), {
            type: 'pie',
            data: {
                labels: ['Creados', 'En Proceso', 'Finalizados'],
                datasets: [{
                    data: [
                        {{ $proyectos->filter(function($p) { return $p->estado->contains('nombre', 'CREADO'); })->count() }},
                        {{ $proyectos->filter(function($p) { return $p->estado->contains('nombre', 'EN PROCESO'); })->count() }},
                        {{ $proyectos->filter(function($p) { return $p->estado->contains('nombre', 'FINALIZADO'); })->count() }}
                    ],
                    backgroundColor: ['#ffc107', '#0d6efd', '#198754']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Gráfico de Inversión Mensual
        const inversionData = {
            labels: {!! json_encode($proyectos->groupBy(function($p) { 
                return $p->fecha_inicio->format('M Y'); 
            })->keys()) !!},
            datasets: [{
                label: 'Inversión Mensual',
                data: {!! json_encode($proyectos->groupBy(function($p) { 
                    return $p->fecha_inicio->format('M Y'); 
                })->map(function($group) { 
                    return $group->sum('mano_obra'); 
                })->values()) !!},
                backgroundColor: '#0d6efd',
                borderColor: '#0d6efd'
            }]
        };

        new Chart(document.getElementById('inversionMensual'), {
            type: 'bar',
            data: inversionData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection