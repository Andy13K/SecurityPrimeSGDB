@extends('layouts.app')

@section('title', 'Recursos')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">Recursos</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Recursos</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('recurso.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Nuevo Recurso
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
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
    </div>

    @forelse($recursos as $tipo => $items)
    <div class="col-12 mb-4">
        <div class="card dashboard-card">
            <div class="card-header">
                <h4 class="card-title">
                    <i class="fas fa-{{ 
                        $tipo === 'Cámaras' ? 'video' : 
                        ($tipo === 'Grabadores' ? 'hdd' : 
                        ($tipo === 'Almacenamiento' ? 'database' : 
                        ($tipo === 'Conectividad' ? 'network-wired' : 'box'))) 
                    }} me-2"></i>
                    {{ $tipo }}
                </h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Precio</th>
                                <th>Costo</th>
                                <th>Margen</th>
                                <th>Proyectos</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $recurso)
                            <tr>
                                <td>{{ $recurso->nombre }}</td>
                                <td>${{ number_format($recurso->precio, 2) }}</td>
                                <td>${{ number_format($recurso->costo, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ ($recurso->precio - $recurso->costo) / $recurso->precio * 100 > 30 ? 'success' : 'warning' }}">
                                        {{ number_format(($recurso->precio - $recurso->costo) / $recurso->precio * 100, 1) }}%
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-primary">
                                        {{ $recurso->proyectos_count }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('recurso.show', $recurso) }}" 
                                           class="btn btn-sm btn-info" 
                                           title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('recurso.edit', $recurso) }}" 
                                           class="btn btn-sm btn-warning" 
                                           title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('recurso.destroy', $recurso) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('¿Está seguro de eliminar este recurso?');">
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
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card dashboard-card">
            <div class="card-body text-center py-5">
                <img src="{{ asset('images/empty-resources.svg') }}" 
                     alt="Sin recursos" 
                     class="mb-3"
                     style="max-width: 200px;">
                <h3>No hay recursos registrados</h3>
                <p class="text-muted">Comienza agregando nuevos recursos al sistema</p>
                <a href="{{ route('recurso.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Agregar Recurso
                </a>
            </div>
        </div>
    </div>
    @endforelse
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Inicializar DataTables si hay tablas
        if ($('.table').length > 0) {
            $('.table').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
                },
                order: [[1, 'desc']],
                responsive: true
            });
        }
    });
</script>
@endpush

@endsection