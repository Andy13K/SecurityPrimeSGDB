@extends('layouts.app')

@section('title', 'Crear Proyecto')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Crear Nuevo Proyecto</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('proyecto.index') }}">Proyectos</a></li>
                <li class="breadcrumb-item active" aria-current="page">Crear</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <form action="{{ route('proyecto.store') }}" method="POST" class="card dashboard-card">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nombre del Proyecto</label>
                        <input type="text" 
                               class="form-control @error('nombre') is-invalid @enderror" 
                               name="nombre" 
                               value="{{ old('nombre') }}" 
                               required>
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Cliente</label>
                        <select class="form-select @error('no_cliente') is-invalid @enderror" 
                                name="no_cliente" 
                                required>
                            <option value="">Seleccione un cliente</option>
                            @foreach($clientes as $cliente)
                                <option value="{{ $cliente->no_cliente }}" 
                                        {{ old('no_cliente') == $cliente->no_cliente ? 'selected' : '' }}>
                                    {{ $cliente->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('no_cliente')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Equipo de Trabajo</label>
                        <select class="form-select @error('no_equipo') is-invalid @enderror" 
                                name="no_equipo" 
                                required>
                            <option value="">Seleccione un equipo</option>
                            @foreach($equipos as $equipo)
                                <option value="{{ $equipo->no_equipo }}" 
                                        {{ old('no_equipo') == $equipo->no_equipo ? 'selected' : '' }}>
                                    {{ $equipo->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('no_equipo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tipo de Entorno</label>
                        <select class="form-select @error('no_tipo_entorno') is-invalid @enderror" 
                                name="no_tipo_entorno" 
                                required>
                            <option value="">Seleccione un tipo de entorno</option>
                            @foreach($tiposEntorno as $tipo)
                                <option value="{{ $tipo->no_entorno }}" 
                                        {{ old('no_tipo_entorno') == $tipo->no_entorno ? 'selected' : '' }}>
                                    {{ $tipo->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('no_tipo_entorno')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Fecha de Inicio</label>
                        <input type="date" 
                               class="form-control @error('fecha_inicio') is-invalid @enderror" 
                               name="fecha_inicio" 
                               value="{{ old('fecha_inicio', date('Y-m-d')) }}" 
                               required>
                        @error('fecha_inicio')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Fecha de Fin</label>
                        <input type="date" 
                               class="form-control @error('fecha_fin') is-invalid @enderror" 
                               name="fecha_fin" 
                               value="{{ old('fecha_fin') }}" 
                               required>
                        @error('fecha_fin')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                                  name="descripcion" 
                                  rows="3" 
                                  required>{{ old('descripcion') }}</textarea>
                        @error('descripcion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Mano de Obra (Q)</label>
                        <input type="number" 
                               class="form-control @error('mano_obra') is-invalid @enderror" 
                               name="mano_obra" 
                               value="{{ old('mano_obra') }}" 
                               step="0.01" 
                               required>
                        @error('mano_obra')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr>

                <h4 class="mb-3">Recursos necesarios</h4>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Recurso</th>
                                <th>Cantidad</th>
                                <th>
                                    <button type="button" 
                                            class="btn btn-sm btn-success" 
                                            onclick="agregarRecurso()">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </th>
                            </tr>
                        </thead>
                        <tbody id="recursos-container">
                            <!-- Los recursos se agregarán aquí dinámicamente -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer text-end">
                <a href="{{ route('proyecto.index') }}" class="btn btn-secondary me-2">Cancelar</a>
                <button type="submit" class="btn btn-custom-primary">Crear Proyecto</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function agregarRecurso() {
        const container = document.getElementById('recursos-container');
        const index = container.children.length;
        
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>
                <select class="form-select" name="recursos[${index}][recurso_id]" required>
                    <option value="">Seleccione un recurso</option>
                    @foreach($recursos as $recurso)
                        <option value="{{ $recurso->no_recurso }}">
                            {{ $recurso->nombre }}
                        </option>
                    @endforeach
                </select>
            </td>
            <td>
                <input type="number" 
                       class="form-control" 
                       name="recursos[${index}][cantidad]" 
                       min="1" 
                       required>
            </td>
            <td>
                <button type="button" 
                        class="btn btn-sm btn-danger" 
                        onclick="this.parentElement.parentElement.remove()">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;
        
        container.appendChild(row);
    }
</script>
@endpush
@endsection