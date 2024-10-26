@extends('layouts.app')

@section('content')
    <h1>Crear Cargo</h1>
    <form action="{{ route('cargo.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="nombre">Nombre del Cargo</label>
            <input type="text" name="nombre" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Guardar</button>
    </form>
@endsection
