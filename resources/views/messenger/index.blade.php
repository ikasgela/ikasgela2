@extends('layouts.app')

@php($count = Auth::user()->newThreadsCount())

@section('content')

    <div class="d-flex flex-row flex-wrap justify-content-between align-items-baseline mb-3">
        <div>
            <h1>{{ __('Tutorship') }}</h1>
        </div>
        <div>
            @if($count > 0)
                @if($count == 1)
                    <h2 class="text-muted font-xl">Un mensaje nuevo</h2>
                @else
                    <h2 class="text-muted font-xl">{{ $count }} mensajes nuevos</h2>
                @endif
            @else
                <h2 class="text-muted font-xl">No hay mensajes nuevos</h2>
            @endif
        </div>
    </div>

    @if(session('tutorial'))
        <div class="callout callout-success b-t-1 b-r-1 b-b-1">
            <small class="text-muted">{{ __('Tutorial') }}</small>
            <p>Aquí puedes iniciar conversaciones para ayudarte a resolver tus dudas sobre las actividades.</p>
        </div>
    @endif

    @include('messenger.partials.flash')

    <a class="btn btn-primary mb-3" href="/messages/create">{{ __('Create new conversation') }}</a>

    @each('messenger.partials.thread', $threads, 'thread', 'messenger.partials.no-threads')
@stop