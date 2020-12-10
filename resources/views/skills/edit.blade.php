@extends('layouts.app')

@section('content')

    @include('partials.titular', ['titular' => __('Edit skill')])

    <div class="card">
        <div class="card-body">

            {!! Form::model($skill, ['route' => ['skills.update', $skill->id], 'method' => 'PUT']) !!}

            <div class="form-group row">
                {!! Form::label('organization_id', __('Organization'), ['class' => 'col-sm-2 col-form-label']) !!}
                <div class="col-sm-10">
                    <select class="form-control" id="organization_id" name="organization_id">
                        @foreach($organizations as $organization)
                            <option
                                value="{{ $organization->id }}" {{ $skill->organization_id == $organization->id ? 'selected' : '' }}>
                                {{ $organization->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{ Form::campoTexto('name', __('Name')) }}
            {{ Form::campoTexto('description', __('Description')) }}
            {{ Form::campoTexto('peso_examen', __('Exam weight')) }}

            {{ Form::campoTexto('minimo_competencias', __('Minimum percent')) }}

            @include('partials.guardar_cancelar')

            @include('layouts.errors')
            {!! Form::close() !!}

        </div>
    </div>
@endsection
