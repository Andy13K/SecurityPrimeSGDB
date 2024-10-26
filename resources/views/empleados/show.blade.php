@extends('layouts.app')

@section('title', 'Detalles del Empleado')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Detalles del Empleado</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('empleado.index') }}">Empleados</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $empleado->nombre }}</li>
            </ol>
        </nav>
    </div>
    <div class="ms-auto">
        <div class="btn-group">
            <a href="{{ route('empleado.edit', $empleado) }}" class="btn btn-primary">
                <i class="fas fa-edit me-2"></i>Editar Empleado
            </a>
            <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="visually-hidden">Toggle Dropdown</span>
            </button>
            <ul class="dropdown-menu">
                <li>
                    <a class="dropdown-item" href="{{ route('empleado.tareas', $empleado) }}">
                        <i class="fas fa-tasks me-2"></i>Ver Tareas
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="{{ route('empleado.rendimiento', $empleado) }}">
                        <i class="fas fa-chart-line me-2"></i>Ver Rendimiento
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form action="{{ route('empleado.destroy', $empleado) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="dropdown-item text-danger delete-confirm">
                            <i class="fas fa-trash me-2"></i>Eliminar Empleado
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
    <br>
</div>

<div class="row">
    <!-- Información Principal -->
    <div class="col-12 col-lg-8">
        <div class="card dashboard-card mb-4">
            <div class="card-header">
                <h4 class="card-title"><i class="fas fa-user me-2"></i>Información Personal</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Nombre Completo</label>
                        <div class="form-control-plaintext">{{ $empleado->nombre }}</div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Teléfono</label>
                        <div class="form-control-plaintext">
                            <i class="fas fa-phone me-2 text-primary"></i>
                            {{ $empleado->telefono }}
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Correo Electrónico</label>
                        <div class="form-control-plaintext">
                            <i class="fas fa-envelope me-2 text-primary"></i>
                            {{ $empleado->correo }}
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Dirección</label>
                        <div class="form-control-plaintext">
                            <i class="fas fa-map-marker-alt me-2 text-primary"></i>
                            {{ $empleado->direccion }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card dashboard-card mb-4">
            <div class="card-header">
                <h4 class="card-title"><i class="fas fa-briefcase me-2"></i>Información Laboral</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label text-muted">Cargo</label>
                        <div class="form-control-plaintext">
                            {{ $empleado->cargo->nombre ?? 'Sin asignar' }}
                            <button type="button" 
                                    class="btn btn-sm btn-outline-primary float-end" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#cambiarCargoModal">
                                Cambiar
                            </button>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label class="form-label text-muted">Equipo de Trabajo</label>
                        <div class="form-control-plaintext">
                            {{ $empleado->equipo->nombre ?? 'Sin asignar' }}
                            <button type="button" 
                                    class="btn btn-sm btn-outline-primary float-end" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#cambiarEquipoModal">
                                Cambiar
                            </button>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label class="form-label text-muted">Especialización</label>
                        <div class="form-control-plaintext">
                            {{ $empleado->especializacion->nombre ?? 'Sin asignar' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tareas Recientes -->
        <div class="card dashboard-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0"><i class="fas fa-tasks me-2"></i>Tareas Recientes</h4>
                <a href="{{ route('empleado.tareas', $empleado) }}" class="btn btn-sm btn-primary">
                    Ver Todas
                </a>
            </div>
            <div class="card-body">
                @if($empleado->tareas->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Tarea</th>
                                    <th>Proyecto</th>
                                    <th>Fecha Fin</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($empleado->tareas->take(5) as $tarea)
                                    <tr>
                                        <td>{{ $tarea->nombre }}</td>
                                        <td>{{ $tarea->proyecto->nombre }}</td>
                                        <td>{{ $tarea->fecha_fin->format('d/m/Y') }}</td>
                                        <td>
                                            <span class="badge bg-{{ $tarea->estado->nombre == 'FINALIZADA' ? 'success' : 
                                                ($tarea->estado->nombre == 'EN PROCESO' ? 'primary' : 'warning') }}">
                                                {{ $tarea->estado->nombre }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-3">
                        <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                        <p class="mb-0">No hay tareas asignadas actualmente.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-12 col-lg-4">
        <!-- Estadísticas -->
        <div class="card dashboard-card mb-4">
            <div class="card-header">
                <h4 class="card-title"><i class="fas fa-chart-pie me-2"></i>Estadísticas</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6 mb-4">
                        <div class="text-center">
                            <h3 class="mb-0">{{ $estadisticasTareas['completadas'] }}</h3>
                            <small class="text-muted">Tareas Completadas</small>
                        </div>
                    </div>
                    <div class="col-6 mb-4">
                        <div class="text-center">
                            <h3 class="mb-0">{{ $estadisticasTareas['pendientes'] }}</h3>
                            <small class="text-muted">Tareas Pendientes</small>
                        </div>
                    </div>
                </div>
                <div class="progress mb-3">
                    @php
                        $porcentajeCompletadas = $estadisticasTareas['total'] > 0 
                            ? ($estadisticasTareas['completadas'] / $estadisticasTareas['total']) * 100 
                            : 0;
                    @endphp
                    <div class="progress-bar bg-success" 
                         role="progressbar" 
                         style="width: {{ $porcentajeCompletadas }}%" 
                         aria-valuenow="{{ $porcentajeCompletadas }}" 
                         aria-valuemin="0" 
                         aria-valuemax="100">
                        {{ number_format($porcentajeCompletadas, 1) }}%
                    </div>
                </div>
                <div class="text-center">
                    <a href="{{ route('empleado.rendimiento', $empleado) }}" class="btn btn-sm btn-outline-primary">
                        Ver Rendimiento Detallado
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Cambiar Cargo -->
<div class="modal fade" id="cambiarCargoModal" tabindex="-1" aria-labelledby="cambiarCargoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('empleado.asignar-cargo', $empleado) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="cambiarCargoModalLabel">Cambiar Cargo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="cargo_no_cargo" class="form-label">Seleccione el nuevo cargo</label>
                        <select class="form-select" id="cargo_no_cargo" name="cargo_no_cargo" required>
                            <option value="">Seleccione un cargo</option>
                            @foreach($cargos as $cargo)
                                <option value="{{ $cargo->no_cargo }}" 
                                        {{ $empleado->cargo_no_cargo == $cargo->no_cargo ? 'selected' : '' }}>
                                    {{ $cargo->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Cambiar Equipo -->
<div class="modal fade" id="cambiarEquipoModal" tabindex="-1" aria-labelledby="cambiarEquipoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('empleado.cambiar-equipo', $empleado) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="cambiarEquipoModalLabel">Cambiar Equipo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="no_equipo" class="form-label">Seleccione el nuevo equipo</label>
                        <select class="form-select" id="no_equipo" name="no_equipo" required>
                            <option value="">Seleccione un equipo</option>
                            @foreach($equipos as $equipo)
                                <option value="{{ $equipo->no_equipo }}" 
                                        {{ $empleado->no_equipo == $equipo->no_equipo ? 'selected' : '' }}>
                                    {{ $equipo->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Confirmar eliminación
        $('.delete-confirm').click(function(e) {
            e.preventDefault();
            if (confirm('¿Está seguro de que desea eliminar este empleado?')) {
                $(this).closest('form').submit();
            }
        });
    });
</script>
@endpush

@endsection