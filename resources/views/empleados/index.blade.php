@extends('layouts.app')

@section('title', 'Empleados')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Empleados</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Empleados</li>
            </ol>
        </nav>
    </div>
    <div class="ms-auto">
        <a href="{{ route('empleado.create') }}" class="btn btn-custom-primary">
            <i class="fas fa-plus me-2"></i>Nuevo Empleado
        </a>
    </div>
    <br>
</div>

<div class="row">
    <div class="col-12">
        <div class="card dashboard-card">
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table custom-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nombre</th>
                                <th>Cargo</th>
                                <th>Equipo</th>
                                <th>Especialización</th>
                                <th>Contacto</th>
                                <th>Tareas</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($empleados as $empleado)
                            <tr>
                                <td>{{ $empleado->no_empleado }}</td>
                                <td>{{ $empleado->nombre }}</td>
                                <td>{{ $empleado->cargo->nombre ?? 'Sin asignar' }}</td>
                                <td>{{ $empleado->equipo->nombre ?? 'Sin asignar' }}</td>
                                <td>{{ $empleado->especializacion->nombre ?? 'Sin asignar' }}</td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <small><i class="fas fa-phone me-2"></i>{{ $empleado->telefono }}</small>
                                        <small><i class="fas fa-envelope me-2"></i>{{ $empleado->correo }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-info me-2">{{ $empleado->tareas_count }}</span>
                                        <a href="{{ route('empleado.tareas', $empleado) }}" 
                                           class="text-primary"
                                           data-bs-toggle="tooltip"
                                           title="Ver tareas">
                                            <i class="fas fa-tasks"></i>
                                        </a>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('empleado.show', $empleado) }}" 
                                           class="btn btn-sm btn-info" 
                                           data-bs-toggle="tooltip" 
                                           title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('empleado.edit', $empleado) }}" 
                                           class="btn btn-sm btn-primary"
                                           data-bs-toggle="tooltip" 
                                           title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('empleado.rendimiento', $empleado) }}" 
                                           class="btn btn-sm btn-success"
                                           data-bs-toggle="tooltip" 
                                           title="Ver rendimiento">
                                            <i class="fas fa-chart-line"></i>
                                        </a>
                                        <form action="{{ route('empleado.destroy', $empleado) }}" 
                                              method="POST" 
                                              class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-danger delete-confirm"
                                                    data-bs-toggle="tooltip" 
                                                    title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
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

@push('scripts')
<script>
    $(document).ready(function() {
        // Inicializar DataTable
        $('.table').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json'
            },
            order: [[0, 'desc']],
            pageLength: 10,
            responsive: true,
            columns: [
                { width: "5%" },    // #
                { width: "15%" },   // Nombre
                { width: "12%" },   // Cargo
                { width: "12%" },   // Equipo
                { width: "12%" },   // Especialización
                { width: "15%" },   // Contacto
                { width: "12%" },   // Tareas
                { width: "17%" }    // Acciones
            ]
        });

        // Inicializar tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

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