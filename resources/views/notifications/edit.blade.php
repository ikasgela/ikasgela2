@extends('layouts.app')

@section('content')

    @include('partials.titular', ['titular' => __('Notification settings')])

    {!! Form::open(['route' => ['notifications.update'], 'method' => 'PUT']) !!}

    <div class="card">
        <div class="card-body pb-1">

            {{ Form::campoCheck('enviar_emails', __('Global'), $user->enviar_emails) }}

        </div>
    </div>

    @include('partials.subtitulo', ['subtitulo' => __('Tutorship')])

    <div class="card">
        <div class="card-body pb-1">

            {{ Form::campoCheck('notificacion_mensaje_recibido', __('Message received'),
            setting_usuario('notificacion_mensaje_recibido'),
            [ !$user->enviar_emails ? 'disabled' : '' ]) }}

        </div>
    </div>

    @include('partials.subtitulo', ['subtitulo' => __('Activities')])

    <div class="card">
        <div class="card-body pb-1">

            {{ Form::campoCheck('notificacion_feedback_recibido', __('Feedback received'),
            setting_usuario('notificacion_feedback_recibido'),
            [ !$user->enviar_emails ? 'disabled' : '' ]) }}

            {{ Form::campoCheck('notificacion_actividad_asignada', __('Activity assigned'),
            setting_usuario('notificacion_actividad_asignada'),
            [ !$user->enviar_emails ? 'disabled' : '' ]) }}

        </div>
    </div>

    @if(Auth::user()->hasRole('profesor'))

        @include('partials.subtitulo', ['subtitulo' => __('Teacher')])

        <div class="card">
            <div class="card-body pb-1">

                {{ Form::campoCheck('notificacion_tarea_enviada', __('Task for review'),
                setting_usuario('notificacion_tarea_enviada'),
                [ !$user->enviar_emails ? 'disabled' : '' ]) }}

            </div>
        </div>

    @endif

    <div class="form-group">
        <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
    </div>

    {!! Form::close() !!}

@endsection
