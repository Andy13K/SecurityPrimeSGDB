@extends('layouts.app')

@section('title', 'Equipos de Trabajo')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">Equipos de Trabajo</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Equipos</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('equipo_trabajo.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Nuevo Equipo
            </a>
        </div>
    </div>
</div>

<div class="row row-cards">
    @forelse($equipos as $equipo)
    <div class="col-12 col-md-6 col-lg-4">
        <div class="card dashboard-card">
            <div class="card-header">
                <h3 class="card-title">{{ $equipo->nombre }}</h3>
            </div>
            <div class="card-body">
                <div class="d-flex flex-column">
                    <div class="mb-3">
                        <i class="fas fa-user-tie me-2"></i>
                        <span class="text-muted">Supervisor:</span>
                        <span class="fw-bold">{{ $equipo->supervisor }}</span>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-users me-2"></i>
                            <span class="text-muted">Miembros:</span>
                            <span class="badge bg-primary ms-2">{{ $equipo->empleados_count }}</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-project-diagram me-2"></i>
                            <span class="text-muted">Proyectos:</span>
                            <span class="badge bg-info ms-2">{{ $equipo->proyectos_count }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-between">
                    <a href="{{ route('equipo_trabajo.show', $equipo) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-eye me-1"></i>Ver Detalles
                    </a>
                    <div class="btn-group">
                        <a href="{{ route('equipo_trabajo.edit', $equipo) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('equipo_trabajo.destroy', $equipo) }}" method="POST" 
                              class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar este equipo?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card dashboard-card">
            <div class="card-body text-center">
                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                <h3>No hay equipos registrados</h3>
                <p class="text-muted">Comienza creando un nuevo equipo de trabajo</p>
                <a href="{{ route('equipo_trabajo.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Crear Equipo
                </a>
            </div>
        </div>
    </div>
    @endforelse
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

@endsection