@extends('layouts.app')

@section('content')
    <h1>Lista de Cargos</h1>
    <a href="{{ route('cargo.create') }}" class="btn btn-primary">Crear Nuevo Cargo</a>
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cargos as $cargo)
                <tr>
                    <td>{{ $cargo->no_cargo }}</td>
                    <td>{{ $cargo->nombre }}</td>
                    <td>
                        <a href="{{ route('cargo.edit', $cargo) }}" class="btn btn-warning">Editar</a>
                        <form action="{{ route('cargo.destroy', $cargo) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
