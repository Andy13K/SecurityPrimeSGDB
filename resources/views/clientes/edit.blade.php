@extends('layouts.app')

@section('title', 'Editar Cliente')

@section('content')
<div class="page-header mb-2">
    <div>
        <h1 class="page-title">Editar Cliente</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('cliente.index') }}">Clientes</a></li>
                <li class="breadcrumb-item active">Editar</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header py-2 bg-light">
                <h3 class="card-title mb-0">Información del Cliente</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('cliente.update', $cliente) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row g-2">
                        <div class="col-md-12 mb-2">
                            <label class="form-label">Nombre del Cliente <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('nombre') is-invalid @enderror" 
                                   name="nombre" 
                                   value="{{ old('nombre', $cliente->nombre) }}" 
                                   required>
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12 mb-2">
                            <label class="form-label">Dirección <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('direccion') is-invalid @enderror" 
                                   name="direccion" 
                                   value="{{ old('direccion', $cliente->direccion) }}" 
                                   required>
                            @error('direccion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-2">
                            <label class="form-label">Correo Electrónico <span class="text-danger">*</span></label>
                            <input type="email" 
                                   class="form-control @error('correo') is-invalid @enderror" 
                                   name="correo" 
                                   value="{{ old('correo', $cliente->correo) }}" 
                                   required>
                            @error('correo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-2">
                            <label class="form-label">Teléfono <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('telefono') is-invalid @enderror" 
                                   name="telefono" 
                                   value="{{ old('telefono', $cliente->telefono) }}" 
                                   pattern="[0-9]{8}"
                                   title="El teléfono debe tener 8 dígitos"
                                   required>
                            @error('telefono')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="text-end mt-3">
                        <a href="{{ route('cliente.show', $cliente) }}" class="btn btn-secondary me-2">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Actualizar Cliente</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection