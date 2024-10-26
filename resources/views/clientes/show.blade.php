@extends('layouts.app')

@section('title', 'Detalles del Cliente')

@section('content')
<div class="page-header mb-2">
    <div>
        <h1 class="page-title">Detalles del Cliente</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('cliente.index') }}">Clientes</a></li>
                <li class="breadcrumb-item active">{{ $cliente->nombre }}</li>
            </ol>
        </nav>
    </div>
    <div class="ms-auto">
        <a href="{{ route('cliente.edit', $cliente) }}" class="btn btn-primary btn-sm">
            <i class="fas fa-edit me-1"></i>Editar Cliente
        </a>

        <a href="{{ route('cliente.dashboard', $cliente) }}" class="btn btn-info btn-sm me-2">
            <i class="fas fa-chart-line me-1"></i>Dashboard Del Cliente
        </a>
    </div>
    
</div>

<div class="row g-2">
    <div class="col-md-4">
        <!-- Información del Cliente -->
        <div class="card mb-2">
            <div class="card-header py-2 bg-light">
                <h3 class="card-title mb-0">Información del Cliente</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered table-sm mb-0">
                    <tr>
                        <td class="table-light" width="35%"><strong>Nombre</strong></td>
                        <td width="65%">{{ $cliente->nombre }}</td>
                    </tr>
                    <tr>
                        <td class="table-light"><strong>Dirección</strong></td>
                        <td>{{ $cliente->direccion }}</td>
                    </tr>
                    <tr>
                        <td class="table-light"><strong>Correo</strong></td>
                        <td>{{ $cliente->correo }}</td>
                    </tr>
                    <tr>
                        <td class="table-light"><strong>Teléfono</strong></td>
                        <td>{{ $cliente->telefono }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Estadísticas -->
        <div class="card mb-2">
            <div class="card-header py-2 bg-light">
                <h3 class="card-title mb-0">Estadísticas</h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <tr>
                            <td>
                                <span class="d-block text-muted">Total Proyectos</span>
                                <h3 class="mb-0">{{ $cliente->proyectos->count() }}</h3>
                            </td>
                            <td>
                                <span class="d-block text-muted">Proyectos Activos</span>
                                <h3 class="mb-0">{{ $cliente->proyectos->whereIn('estado.nombre', ['EN PROCESO'])->count() }}</h3>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="d-block text-muted">Proyectos Completados</span>
                                <h3 class="mb-0">{{ $cliente->proyectos->whereIn('estado.nombre', ['FINALIZADO'])->count() }}</h3>
                            </td>
                            <td>
                                <span class="d-block text-muted">Proyectos Pendientes</span>
                                <h3 class="mb-0">{{ $cliente->proyectos->whereIn('estado.nombre', ['CREADO'])->count() }}</h3>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <!-- Proyectos del Cliente -->
        <div class="card">
            <div class="card-header py-2 bg-light">
                <h3 class="card-title mb-0">Proyectos Asignados</h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm mb-0">
                        <thead>
                            <tr class="table-light">
                                <th width="25%">Nombre</th>
                                <th width="20%">Equipo</th>
                                <th width="15%">Fecha Inicio</th>
                                <th width="15%">Fecha Fin</th>
                                <th width="15%">Estado</th>
                                <th width="10%">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($cliente->proyectos as $proyecto)
                                <tr>
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
                                    <td class="text-center">
                                        <a href="{{ route('proyecto.show', $proyecto) }}" 
                                           class="btn btn-sm btn-info" 
                                           title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>

                                <div class="ms-auto">
                                
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No hay proyectos asignados</td>
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