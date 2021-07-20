@extends('layouts.app')

@section('content')

    @include('partials.titular', ['titular' => __('Courses'), 'subtitulo' => $organization->name])

    @include('partials.tutorial', [
    'color' => 'c-callout-success',
    'texto' => 'Aquí puedes matricularte en otros cursos disponibles y cambiar de un curso a otro.'
    ])

    @forelse($periods as $period)
        @php($total = 0)
        @foreach($period->categories as $category)
            @if($loop->first)
                <h3>{{ $period->name }}</h3>
            @endif
            @foreach($category->cursos as $curso)
                @if($loop->first)
                    <div class="row">
                @endif
                @if(!in_array($curso->id, $matricula) && $curso->matricula_abierta || in_array($curso->id, $matricula))
                    @include('alumnos.partials.tarjeta_curso')
                    @php($total += 1)
                @endif
                @if($loop->last)
                    </div>
                @endif
            @endforeach
        @endforeach
        @if($loop->last && $total == 0)
            <div class="row">
                <div class="col-12">
                    <p>{{ __('There are no courses available') }}.</p>
                </div>
            </div>
        @endif
    @empty
        <div class="row">
            <div class="col-12">
                <p>{{ __('There are no courses available') }}.</p>
            </div>
        </div>
    @endforelse
@endsection
