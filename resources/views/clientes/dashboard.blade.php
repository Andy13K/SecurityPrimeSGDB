@extends('layouts.app')

@section('title', 'Dashboard Cliente')

@section('content')
<div class="page-header mb-2">
    <div>
        <h1 class="page-title">Dashboard del Cliente</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('cliente.index') }}">Clientes</a></li>
                <li class="breadcrumb-item active">{{ $cliente->nombre }}</li>
            </ol>
        </nav>
    </div>
    <div class="ms-auto">
        <a href="{{ route('cliente.show', $cliente) }}" class="btn btn-primary btn-sm">
            <i class="fas fa-eye me-1"></i>Ver Detalles
        </a>
    </div>
</div>

<div class="row g-2">
    <!-- Tarjetas de Estadísticas -->
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white mb-1">Total Proyectos</h6>
                        <h3 class="mb-0 text-white">{{ $stats['total_proyectos'] }}</h3>
                    </div>
                    <div class="fs-3">
                        <i class="fas fa-project-diagram"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white mb-1">Proyectos Activos</h6>
                        <h3 class="mb-0 text-white">{{ $stats['proyectos_activos'] }}</h3>
                    </div>
                    <div class="fs-3">
                        <i class="fas fa-tasks"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-warning">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">Inversión Total</h6>
                        <h3 class="mb-0">Q{{ number_format($stats['inversion_total'], 2) }}</h3>
                    </div>
                    <div class="fs-3">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white mb-1">Último Proyecto</h6>
                        <p class="mb-0 text-white">{{ $stats['ultimo_proyecto']->fecha_inicio->format('d/m/Y') ?? 'N/A' }}</p>
                    </div>
                    <div class="fs-3">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Información del Cliente -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header py-2 bg-light">
                <h3 class="card-title mb-0">Información de Contacto</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered table-sm mb-0">
                    <tr>
                        <td class="table-light" width="30%"><strong>Nombre</strong></td>
                        <td>{{ $cliente->nombre }}</td>
                    </tr>
                    <tr>
                        <td class="table-light"><strong>Correo</strong></td>
                        <td>{{ $cliente->correo }}</td>
                    </tr>
                    <tr>
                        <td class="table-light"><strong>Teléfono</strong></td>
                        <td>{{ $cliente->telefono }}</td>
                    </tr>
                    <tr>
                        <td class="table-light"><strong>Dirección</strong></td>
                        <td>{{ $cliente->direccion }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Proyectos Recientes -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header py-2 bg-light d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0">Proyectos Recientes</h3>
                <a href="{{ route('cliente.proyectos', $cliente) }}" class="btn btn-primary btn-sm">
                    Ver Todos
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm mb-0">
                        <thead>
                            <tr class="table-light">
                                <th>Proyecto</th>
                                <th>Equipo</th>
                                <th>Inicio</th>
                                <th>Estado</th>
                                <th>Progreso</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($cliente->proyectos()->latest('fecha_inicio')->take(5)->get() as $proyecto)
                                <tr>
                                    <td>{{ $proyecto->nombre }}</td>
                                    <td>{{ $proyecto->equipo->nombre }}</td>
                                    <td>{{ $proyecto->fecha_inicio->format('d/m/Y') }}</td>
                                    <td class="text-center">
                                        @foreach($proyecto->estado as $estado)
                                            <span class="badge bg-{{ $estado->nombre == 'CREADO' ? 'warning' : 
                                                ($estado->nombre == 'EN PROCESO' ? 'primary' : 'success') }}">
                                                {{ $estado->nombre }}
                                            </span>
                                        @endforeach
                                    </td>
                                    <td class="text-center">
                                        <div class="progress" style="height: 15px;">
                                            <div class="progress-bar bg-success" 
                                                 role="progressbar" 
                                                 style="width: {{ $proyecto->calcularPorcentajeCompletado() }}%">
                                                {{ number_format($proyecto->calcularPorcentajeCompletado(), 0) }}%
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No hay proyectos recientes</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection