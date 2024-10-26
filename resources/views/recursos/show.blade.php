@extends('layouts.app')

@section('title', $recurso->nombre)

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">{{ $recurso->nombre }}</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('recurso.index') }}">Recursos</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $recurso->nombre }}</li>
                </ol>
            </nav>
        </div>
        <div class="btn-group">
            <a href="{{ route('recurso.edit', $recurso) }}" class="btn btn-warning">
                <i class="fas fa-edit me-2"></i>Editar
            </a>
            <a href="{{ route('recurso.reporte-uso', $recurso) }}" class="btn btn-info">
                <i class="fas fa-chart-bar me-2"></i>Reporte de Uso
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

<div class="row">
    <!-- Información del Recurso -->
    <div class="col-12 col-lg-4">
        <div class="card dashboard-card mb-4">
            <div class="card-header">
                <h4 class="card-title"><i class="fas fa-info-circle me-2"></i>Información del Recurso</h4>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <span class="text-muted">Precio de Venta:</span>
                        <span class="float-end fw-bold">${{ number_format($recurso->precio, 2) }}</span>
                    </li>
                    <li class="list-group-item">
                        <span class="text-muted">Costo:</span>
                        <span class="float-end">${{ number_format($recurso->costo, 2) }}</span>
                    </li>
                    <li class="list-group-item">
                        <span class="text-muted">Margen:</span>
                        <span class="badge bg-{{ $stats['margen_porcentaje'] > 30 ? 'success' : 'warning' }} float-end">
                            {{ number_format($stats['margen_porcentaje'], 1) }}%
                        </span>
                    </li>
                    <li class="list-group-item">
                        <span class="text-muted">Total Asignado:</span>
                        <span class="badge bg-primary float-end">{{ $stats['total_asignado'] }} unidades</span>
                    </li>
                    <li class="list-group-item">
                        <span class="text-muted">Proyectos Activos:</span>
                        <span class="badge bg-info float-end">{{ $stats['proyectos_activos'] }}</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Asignar a Proyecto -->
        <div class="card dashboard-card">
            <div class="card-header">
                <h4 class="card-title"><i class="fas fa-plus-circle me-2"></i>Asignar a Proyecto</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('recurso.asignar-proyecto', $recurso) }}" method="POST" class="needs-validation" novalidate>
                    @csrf
                    <div class="mb-3">
                        <label class="form-label" for="proyecto_id">Proyecto</label>
                        <select class="form-select @error('proyecto_id') is-invalid @enderror" 
                                id="proyecto_id" 
                                name="proyecto_id" 
                                required>
                            <option value="">Seleccione un proyecto</option>
                            @foreach(\App\Models\Proyecto::whereHas('estado', function($q) {
                                $q->whereIn('nombre', ['CREADO', 'EN PROCESO']);
                            })->get() as $proyecto)
                                <option value="{{ $proyecto->no_proyecto }}">
                                    {{ $proyecto->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('proyecto_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="cantidad">Cantidad</label>
                        <input type="number" 
                               class="form-control @error('cantidad') is-invalid @enderror" 
                               id="cantidad" 
                               name="cantidad" 
                               min="1" 
                               value="1"
                               required>
                        @error('cantidad')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-plus me-2"></i>Asignar
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Proyectos que usan este recurso -->
    <div class="col-12 col-lg-8">
        <div class="card dashboard-card">
            <div class="card-header">
                <h4 class="card-title"><i class="fas fa-project-diagram me-2"></i>Proyectos Asignados</h4>
            </div>
            <div class="card-body">
                @if($recurso->proyectos->isEmpty())
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-box-open fa-3x mb-3"></i>
                        <h5>Sin Asignaciones</h5>
                        <p>Este recurso no ha sido asignado a ningún proyecto.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Proyecto</th>
                                    <th>Cantidad</th>
                                    <th>Fecha Asignación</th>
                                    <th>Estado</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recurso->proyectos as $proyecto)
                                <tr>
                                    <td>
                                        <a href="{{ route('proyecto.show', $proyecto) }}">
                                            {{ $proyecto->nombre }}
                                        </a>
                                    </td>
                                    <td>{{ $proyecto->pivot->cantidad_asignada }}</td>
                                    <td>{{ \Carbon\Carbon::parse($proyecto->pivot->fecha)->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $proyecto->estado->first()->nombre === 'FINALIZADO' ? 'success' : 'primary' }}">
                                            {{ $proyecto->estado->first()->nombre }}
                                        </span>
                                    </td>
                                    <td>${{ number_format($proyecto->pivot->cantidad_asignada * $recurso->precio, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Inicializar Select2
        $('#proyecto_id').select2({
            theme: 'bootstrap-5',
            width: '100%'
        });

        // Inicializar DataTable si hay datos
        if ($('.table').length > 0) {
            $('.table').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
                },
                order: [[2, 'desc']],
                responsive: true
            });
        }
    });
</script>
@endpush

@endsection