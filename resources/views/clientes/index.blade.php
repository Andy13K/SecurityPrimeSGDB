@extends('layouts.app')

@section('title', 'Clientes')

@section('content')
<div class="page-header mb-2">
    <div>
        <h1 class="page-title">Gestión de Clientes</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Clientes</li>
            </ol>
        </nav>
    </div>
    <div class="ms-auto">
        <a href="{{ route('cliente.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i>Nuevo Cliente
        </a>
    </div>
</div>

<div class="row g-2">
    <div class="col-12">
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm mb-0">
                        <thead>
                            <tr class="table-light text-center">
                                <th width="5%">#</th>
                                <th width="25%">Nombre</th>
                                <th width="20%">Correo</th>
                                <th width="15%">Teléfono</th>
                                <th width="15%">Proyectos</th>
                                <th width="20%">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($clientes as $cliente)
                                <tr>
                                    <td class="text-center">{{ $cliente->no_cliente }}</td>
                                    <td>{{ $cliente->nombre }}</td>
                                    <td>{{ $cliente->correo }}</td>
                                    <td class="text-center">{{ $cliente->telefono }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-info">{{ $cliente->proyectos_count }} proyecto(s)</span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('cliente.show', $cliente) }}" 
                                           class="btn btn-sm btn-info me-1" 
                                           title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('cliente.edit', $cliente) }}" 
                                           class="btn btn-sm btn-primary me-1" 
                                           title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('cliente.destroy', $cliente) }}" 
                                              method="POST" 
                                              class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-danger" 
                                                    title="Eliminar"
                                                    onclick="return confirm('¿Está seguro de eliminar este cliente?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No hay clientes registrados</td>
                                </tr>
                            @endforelse
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
        $('.table').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json'
            },
            pageLength: 10,
            responsive: true,
            order: [[0, 'desc']],
        });
    });
</script>
@endpush
@endsection