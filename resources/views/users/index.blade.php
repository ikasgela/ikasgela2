@extends('layouts.app')

@section('content')

    <div class="row mb-3">
        <div class="col-md">
            <h1>Panel de control</h1>
        </div>
    </div>

    <table class="table">
        <thead class="thead-dark">
        <tr>
            <th></th>
            <th>Nombre</th>
            <th>Email</th>
            <th>Asignadas</th>
            <th>Para revisar</th>
            <th>Acciones</th>
        </tr>
        </thead>
        <tbody>
        @foreach($usuarios as $usuario)
            <tr>
                <td><img style="height:35px;" src="{{ $usuario->avatar_url()}}"/></td>
                <td>{{ $usuario->name }}</td>
                <td><a href="mailto:{{ $usuario->email }}" class="card-link">{{ $usuario->email }}</a></td>
                <td>{{ count($usuario->actividades_asignadas()) }}</td>
                <td>{{ count($usuario->actividades_enviadas()) }}</td>
                <td><a href="{{ route('tareas.index', [$usuario->id]) }}"
                       class='btn btn-outline-dark'>Asignar actividades</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection
