@extends('layouts.app')

@section('content')

    @include('partials.titular', ['titular' => __('Project cloner')])

    <div class="card">
        <div class="card-body">

            {!! Form::open(['route' => 'intellij_projects.duplicar']) !!}

            {{ Form::campoTexto('origen', __('Source'), session('intellij_origen', 'root/programacion.plantillas.proyecto-intellij-java'), ['placeholder' => 'root/programacion.plantillas.proyecto-intellij-java']) }}
            {{ Form::campoTexto('destino', __('Destination'), session('intellij_destino'), ['placeholder' => 'root/copia (opcional)']) }}
            {{ Form::campoTexto('nombre', __('New project description'), '', ['placeholder' => 'Hola Mundo (opcional, mantiene el original)']) }}
            {{ Form::campoCheck('crear_recurso', __('Create associated resource'), true) }}

            <button type="submit" class="btn btn-primary">{{ __('Clone') }}</button>

            {!! Form::close() !!}

        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="thead-dark">
            <tr>
                <th>#</th>
                <th>{{ __('Name') }}</th>
                <th>{{ __('Description') }}</th>
                <th>{{ __('Repository') }}</th>
                <th>{{ __('Actions') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($proyectos as $proyecto)
                <tr>
                    <td>{{ $proyecto['id'] }}</td>
                    <td>{{ $proyecto['name'] }}</td>
                    <td>{{ $proyecto['description'] }}</td>
                    <td>@include('partials.link_gitea', ['proyecto' => $proyecto ])</td>
                    <td class="text-nowrap">
                        {!! Form::open(['route' => ['intellij_projects.borrar', $proyecto['id']], 'method' => 'DELETE']) !!}
                        <div class='btn-group'>
                            @include('partials.boton_borrar')
                        </div>
                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

@endsection
