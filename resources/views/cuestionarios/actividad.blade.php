@extends('layouts.app')

@section('content')

    @include('partials.titular', ['titular' => __('Resources: Questionnaires')])

    <div class="row">
        <div class="col-md-12">
            {{-- Tarjeta --}}
            <div class="card">
                <div class="card-header">{{ $actividad->unidad->slug.'/'.$actividad->slug }}</div>
                <div class="card-body pb-1">
                    <h2>{{ $actividad->nombre }}</h2>
                    <p>{{ $actividad->descripcion }}</p>
                </div>
            </div>
            {{-- Fin tarjeta--}}
        </div>
    </div>

    @include('partials.subtitulo', ['subtitulo' => __('Assigned resources')])

    @if(count($cuestionarios) > 0 )
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="thead-dark">
                <tr>
                    <th>#</th>
                    <th>{{ __('Title') }}</th>
                    <th>{{ __('Description') }}</th>
                    <th>{{ __('Template') }}</th>
                    <th>{{ __('Actions') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($cuestionarios as $cuestionario)
                    <tr>
                        <td>{{ $cuestionario->id }}</td>
                        <td>{{ $cuestionario->titulo }}</td>
                        <td>{{ $cuestionario->descripcion }}</td>
                        <td>{!! $cuestionario->plantilla ? '<i class="fas fa-check text-success"></i>' : '<i class="fas fa-times text-danger"></i>' !!}</td>
                        <td>
                            <form method="POST"
                                  action="{{ route('cuestionarios.desasociar', ['actividad' => $actividad->id, '$cuestionario'=>$cuestionario->id]) }}">
                                @csrf
                                @method('DELETE')
                                <div class='btn-group'>
                                    @include('partials.boton_borrar')
                                </div>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="row">
            <div class="col-md">
                <p>No hay elementos.</p>
            </div>
        </div>
    @endif

    @include('partials.subtitulo', ['subtitulo' => __('Available resources')])

    @if(count($disponibles) > 0)
        <form method="POST" action="{{ route('cuestionarios.asociar', ['actividad' => $actividad->id]) }}">
            @csrf

            <div class="table-responsive">
                <table class="table">
                    <thead class="thead-dark">
                    <tr>
                        <th></th>
                        <th>#</th>
                        <th>{{ __('Title') }}</th>
                        <th>{{ __('Description') }}</th>
                        <th>{{ __('Template') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($disponibles as $cuestionario)
                        <tr>
                            <td><input type="checkbox" name="seleccionadas[]" value="{{ $cuestionario->id }}"></td>
                            <td>{{ $cuestionario->id }}</td>
                            <td>{{ $cuestionario->titulo }}</td>
                            <td>{{ $cuestionario->descripcion }}</td>
                            <td>{!! $cuestionario->plantilla ? '<i class="fas fa-check text-success"></i>' : '<i class="fas fa-times text-danger"></i>' !!}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            @include('layouts.errors')

            <div>
                <button type="submit" class="btn btn-primary mb-4">{{ __('Save assigment') }}</button>
            </div>

        </form>
    @else
        <div class="row">
            <div class="col-md">
                <p>No hay elementos.</p>
            </div>
        </div>
    @endif

    <div>
        @include('partials.backbutton')
    </div>
@endsection