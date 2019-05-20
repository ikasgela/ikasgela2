@extends('layouts.app')

@section('content')

    @include('partials.titular', ['titular' => __('New activity')])

    <div class="card">
        <div class="card-body">

            {!! Form::open(['route' => 'actividades.store']) !!}

            <div class="form-group row">
                {!! Form::label('unidad', __('Unit'), ['class' => 'col-sm-2 col-form-label']) !!}
                <div class="col-sm-10">
                    <select class="form-control" id="unidad_id" name="unidad_id">
                        @foreach($unidades as $unidad)
                            <option value="{{ $unidad->id }}" {{ session('profesor_unidad_actual') == $unidad->id ? 'selected' : '' }}>{{ $unidad->nombre }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{ Form::campoTexto('nombre', __('Name')) }}
            {{ Form::campoTexto('descripcion', __('Description')) }}
            {{ Form::campoTexto('slug', __('Slug')) }}
            {{ Form::campoTexto('puntuacion', __('Score'), 100) }}
            {{ Form::campoCheck('plantilla', __('Template'), true) }}

            <div class="form-group row">
                {!! Form::label('siguiente_id', __('Next'), ['class' => 'col-sm-2 col-form-label']) !!}
                <div class="col-sm-10">
                    <select class="form-control" id="siguiente_id" name="siguiente_id">
                        <option value="">{{ __('--- None ---') }}</option>
                        @foreach($actividades as $temp)
                            <option value="{{ $temp->id }}">{{ $temp->slug }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            {{ Form::campoCheck('final', __('Final')) }}
            {{ Form::campoCheck('auto_avance', __('Auto advance')) }}

            @include('partials.guardar_cancelar')

            @include('layouts.errors')
            {!! Form::close() !!}

        </div>
    </div>
@endsection
