@extends('layouts.app')

@section('title', 'Proyectos')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Proyectos</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Proyectos</li>
            </ol>
        </nav>
    </div>
    <div class="ms-auto">
        <a href="{{ route('proyecto.create') }}" class="btn btn-custom-primary">
            <i class="fas fa-plus me-2"></i>Nuevo Proyecto
        </a>
    </div>
    <br>
</div>

<div class="row">
    <div class="col-12">
        <div class="card dashboard-card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table custom-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nombre</th>
                                <th>Cliente</th>
                                <th>Equipo</th>
                                <th>Fecha Inicio</th>
                                <th>Fecha Fin</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($proyectos as $proyecto)
                            <tr>
                                <td>{{ $proyecto->no_proyecto }}</td>
                                <td>{{ $proyecto->nombre }}</td>
                                <td>{{ $proyecto->cliente->nombre ?? 'N/A' }}</td>
                                <td>{{ $proyecto->equipo->nombre ?? 'N/A' }}</td>
                                <td>
                                    @if(is_string($proyecto->fecha_inicio))
                                        {{ \Carbon\Carbon::parse($proyecto->fecha_inicio)->format('d/m/Y') }}
                                    @elseif($proyecto->fecha_inicio)
                                        {{ $proyecto->fecha_inicio->format('d/m/Y') }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>
                                    @if(is_string($proyecto->fecha_fin))
                                        {{ \Carbon\Carbon::parse($proyecto->fecha_fin)->format('d/m/Y') }}
                                    @elseif($proyecto->fecha_fin)
                                        {{ $proyecto->fecha_fin->format('d/m/Y') }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>
                                    @forelse($proyecto->estado as $estado)
                                        <span class="badge bg-{{ $estado->nombre == 'CREADO' ? 'warning' : 
                                            ($estado->nombre == 'EN PROCESO' ? 'primary' : 'success') }}">
                                            {{ $estado->nombre }}
                                        </span>
                                    @empty
                                        <span class="badge bg-secondary">Sin Estado</span>
                                    @endforelse
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('proyecto.show', $proyecto) }}" 
                                           class="btn btn-sm btn-info" 
                                           data-bs-toggle="tooltip" 
                                           title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('proyecto.edit', $proyecto) }}" 
                                           class="btn btn-sm btn-primary"
                                           data-bs-toggle="tooltip" 
                                           title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('proyecto.destroy', $proyecto) }}" 
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
                { width: "5%" },   // #
                { width: "20%" },  // Nombre
                { width: "15%" },  // Cliente
                { width: "15%" },  // Equipo
                { width: "10%" },  // Fecha Inicio
                { width: "10%" },  // Fecha Fin
                { width: "10%" },  // Estado
                { width: "15%" }   // Acciones
            ]
        });

        // Confirmar eliminación
        $('.delete-confirm').click(function(e) {
            e.preventDefault();
            if (confirm('¿Está seguro de que desea eliminar este proyecto?')) {
                $(this).closest('form').submit();
            }
        });
    });
</script>
@endpush
@endsection