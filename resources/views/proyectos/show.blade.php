@extends('layouts.app')

@section('title', 'Detalles del Proyecto')

@section('content')
<div class="page-header mb-3">
    <div>
        <h1 class="page-title">Detalles del Proyecto</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('proyecto.index') }}">Proyectos</a></li>
                <li class="breadcrumb-item active">{{ $proyecto->nombre }}</li>
            </ol>
        </nav>
    </div>
    <div class="ms-auto">
        <a href="{{ route('proyecto.edit', $proyecto) }}" class="btn btn-custom-primary">
            <i class="fas fa-edit me-2"></i>Editar Proyecto
        </a>
    </div>
</div>

<div class="row g-3">
    <!-- Columna Principal -->
    <div class="col-md-8">
        <!-- Información General -->
        <div class="card dashboard-card mb-3">
            <div class="card-header py-2 d-flex justify-content-between align-items-center bg-light">
                <h3 class="card-title mb-0">Información General</h3>
                <div>
                    @forelse($proyecto->estado as $estado)
                        <span class="badge bg-{{ $estado->nombre == 'CREADO' ? 'warning' : 
                            ($estado->nombre == 'EN PROCESO' ? 'primary' : 'success') }} px-3">
                            Estado Actual: {{ $estado->nombre }}
                        </span>
                    @empty
                        <span class="badge bg-secondary">Sin Estado</span>
                    @endforelse
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead>
                            <tr class="table-light text-center">
                                <th colspan="2">Información del Proyecto</th>
                                <th colspan="2">Información de Planificación</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="table-light" width="15%"><strong>Nombre del Proyecto</strong></td>
                                <td width="35%">{{ $proyecto->nombre }}</td>
                                <td class="table-light" width="15%"><strong>Fecha de Inicio</strong></td>
                                <td width="35%">{{ $proyecto->formatDate($proyecto->fecha_inicio) }}</td>
                            </tr>
                            <tr>
                                <td class="table-light"><strong>Cliente Asignado</strong></td>
                                <td>{{ $proyecto->cliente->nombre }}</td>
                                <td class="table-light"><strong>Fecha de Finalización</strong></td>
                                <td>{{ $proyecto->formatDate($proyecto->fecha_fin) }}</td>
                            </tr>
                            <tr>
                                <td class="table-light"><strong>Equipo Responsable</strong></td>
                                <td>{{ $proyecto->equipo->nombre }}</td>
                                <td class="table-light"><strong>Tiempo Restante</strong></td>
                                <td>
                                    <span class="badge bg-{{ $proyecto->getDiasRestantes() > 30 ? 'success' : 
                                        ($proyecto->getDiasRestantes() > 15 ? 'warning' : 'danger') }} px-3">
                                        {{ $proyecto->getDiasRestantes() }} días restantes
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="table-light"><strong>Tipo de Entorno</strong></td>
                                <td>{{ $proyecto->tipoEntorno->nombre }}</td>
                                <td class="table-light"><strong>Duración Total</strong></td>
                                <td>{{ $proyecto->getDuracionEnDias() }} días planificados</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- Descripción -->
                <div class="p-3 bg-light border-top">
                    <div class="row">
                        <div class="col-md-2">
                            <strong>Descripción del Proyecto:</strong>
                        </div>
                        <div class="col-md-10">
                            {{ $proyecto->descripcion }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Recursos Asignados -->
        <div class="card dashboard-card mb-3">
            <div class="card-header py-2">
                <h3 class="card-title mb-0">Recursos Asignados</h3>
            </div>
            <div class="card-body p-3">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm mb-0">
                        <thead>
                            <tr class="table-light">
                                <th>Recurso</th>
                                <th class="text-center">Cantidad</th>
                                <th class="text-end">Costo Total</th>
                                <th class="text-end">Precio Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($proyecto->recursos as $recurso)
                                <tr>
                                    <td>{{ $recurso->nombre }}</td>
                                    <td class="text-center">{{ $recurso->pivot->cantidad_asignada }}</td>
                                    <td class="text-end">Q{{ number_format($recurso->costo * $recurso->pivot->cantidad_asignada, 2) }}</td>
                                    <td class="text-end">Q{{ number_format($recurso->precio * $recurso->pivot->cantidad_asignada, 2) }}</td>
                                </tr>
                            @endforeach
                            <tr class="table-info">
                                <td colspan="2"><strong>Mano de Obra</strong></td>
                                <td class="text-end">Q{{ number_format($proyecto->mano_obra, 2) }}</td>
                                <td class="text-end">Q{{ number_format($proyecto->mano_obra * 1.3, 2) }}</td>
                            </tr>
                            <tr class="table-success">
                                <td colspan="2"><strong>Total</strong></td>
                                <td class="text-end"><strong>Q{{ number_format($proyecto->calcularCostoTotal(), 2) }}</strong></td>
                                <td class="text-end"><strong>Q{{ number_format($proyecto->calcularPrecioTotal(), 2) }}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Tareas -->
        <div class="card dashboard-card">
            <div class="card-header py-2 d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0">Tareas del Proyecto</h3>
                <span class="badge bg-info">Progreso: {{ number_format($proyecto->calcularPorcentajeCompletado(), 1) }}%</span>
            </div>
            <div class="card-body p-3">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm mb-0">
                        <thead>
                            <tr class="table-light">
                                <th>Descripción</th>
                                <th>Empleado</th>
                                <th>Estado</th>
                                <th>Fecha Inicio</th>
                                <th>Fecha Fin</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($proyecto->tareas as $tarea)
                                <tr>
                                    <td>{{ $tarea->descripcion }}</td>
                                    <td>{{ $tarea->empleado->nombre ?? 'Sin asignar' }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-{{ $tarea->estado->nombre == 'FINALIZADA' ? 'success' : 
                                            ($tarea->estado->nombre == 'ASIGNADA' ? 'primary' : 'warning') }}">
                                            {{ $tarea->estado->nombre }}
                                        </span>
                                    </td>
                                    <td>{{ date('d/m/Y', strtotime($tarea->fecha_inicio)) }}</td>
                                    <td>{{ date('d/m/Y', strtotime($tarea->fecha_fin)) }}</td>
                                    <td>{{ $tarea->getEstadoFormateado() }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Columna Lateral -->
    <div class="col-md-4">
        <!-- Resumen -->
        <div class="card dashboard-card mb-3">
            <div class="card-header py-2">
                <h3 class="card-title mb-0">Resumen del Proyecto</h3>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                        <strong>Duración Total</strong>
                        <span>{{ $proyecto->getDuracionEnDias() }} días</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                        <strong>Días Restantes</strong>
                        <span>{{ $proyecto->getDiasRestantes() }} días</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                        <strong>Total Tareas</strong>
                        <span>{{ $proyecto->tareas->count() }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                        <strong>Tareas Completadas</strong>
                        <span>{{ $proyecto->tareas->where('estado.nombre', 'FINALIZADA')->count() }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                        <strong>Progreso</strong>
                        <span>{{ number_format($proyecto->calcularPorcentajeCompletado(), 1) }}%</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Información Financiera -->
        <div class="card dashboard-card mb-3">
            <div class="card-header py-2">
                <h3 class="card-title mb-0">Información Financiera</h3>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                        <strong>Costo Total</strong>
                        <span class="text-danger">Q{{ number_format($proyecto->calcularCostoTotal(), 2) }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                        <strong>Precio Total</strong>
                        <span class="text-success">Q{{ number_format($proyecto->calcularPrecioTotal(), 2) }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                        <strong>Margen Esperado</strong>
                        <span class="text-primary">Q{{ number_format($proyecto->calcularMargen(), 2) }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                        <strong>Mano de Obra</strong>
                        <span>Q{{ number_format($proyecto->mano_obra, 2) }}</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Estado del Proyecto -->
        <div class="card dashboard-card">
            <div class="card-header py-2">
                <h3 class="card-title mb-0">Cambiar Estado</h3>
            </div>
            <div class="card-body p-3">
                <form action="{{ route('proyecto.cambiarEstado', $proyecto) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <select name="estado_id" class="form-select form-select-sm @error('estado_id') is-invalid @enderror" required>
                            <option value="">Seleccione un estado</option>
                            @foreach(App\Models\EstadoProyecto::all() as $estado)
                                <option value="{{ $estado->no_estado }}"
                                    {{ $proyecto->estado->contains('no_estado', $estado->no_estado) ? 'selected' : '' }}>
                                    {{ $estado->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('estado_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-custom-primary btn-sm w-100">
                        Actualizar Estado
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $('.table').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json'
            },
            pageLength: 10,
            responsive: true,
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                 '<"row"<"col-sm-12"tr>>' +
                 '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
            buttons: []
        });
    });
</script>
@endpush
@endsection