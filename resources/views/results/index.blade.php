@extends('layouts.app')

@section('content')

    @if(!is_null($curso))
        @include('partials.titular', ['titular' => __('Results'), 'subtitulo' => $curso->category->period->organization->name.' » '.$curso->category->period->name.' » '.$curso->nombre  ?? '' ])
    @else
        @include('partials.titular', ['titular' => __('Results')])
    @endif

    @if(session('tutorial'))
        <div class="callout callout-success b-t-1 b-r-1 b-b-1">
            <small class="text-muted">{{ __('Tutorial') }}</small>
            <p>Aquí aparecerán los resultados de las competencias asociadas al curso.</p>
        </div>
    @endif

    @if(Auth::user()->hasAnyRole(['profesor', 'tutor']))
        {!! Form::open(['route' => ['results.alumno'], 'method' => 'POST']) !!}
        @include('partials.desplegable_usuarios')
        {!! Form::close() !!}
    @endif

    @if(Auth::user()->hasAnyRole(['profesor']))
        <div class="row mt-3 mb-0">
            <div class="col-md-4">
                <div class="card mb-3 {{ $nota_final >= 5 ? 'bg-success text-white' : 'bg-warning text-dark' }}">
                    <div class="card-header">Calificación</div>
                    <div class="card-body text-center">
                        <p class="card-text" style="font-size:150%;">{{ $nota_final }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-success mb-3">
                    <div class="card-header">Actividades obligatorias</div>
                    <div class="card-body text-center">
                        <p class="card-text" style="font-size:150%;">Sin completar</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-success mb-3">
                    <div class="card-header">Pruebas de evaluación</div>
                    <div class="card-body text-center">
                        <p class="card-text" style="font-size:150%;">Superadas</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @include('partials.subtitulo', ['subtitulo' => __('Skills development')])

    @if(count($skills_curso) > 0)
        {{-- Tarjeta --}}
        <div class="card">
            <div class="card-body">
                @foreach ($skills_curso as $skill)
                    <h5 class="card-title">{{ $skill->name }}</h5>
                    <p class="ml-5">{{ $skill->description }}</p>
                    <div class="ml-5 progress" style="height: 24px;">
                        @php($porcentaje = $resultados[$skill->id]->actividad > 0 ? round($resultados[$skill->id]->tarea/$resultados[$skill->id]->actividad*100) : 0)
                        <div class="progress-bar {{ $porcentaje<50 ? 'bg-warning text-dark' : 'bg-success' }}"
                             role="progressbar"
                             style="width: {{ $porcentaje }}%;"
                             aria-valuenow="{{ $porcentaje }}"
                             aria-valuemin="0"
                             aria-valuemax="100">@if($porcentaje>0){{ $porcentaje }}%@endif
                        </div>
                    </div>
                    <div class="text-muted small text-right">
                        {{ $resultados[$skill->id]->tarea + 0 }}/{{ $resultados[$skill->id]->actividad + 0 }}
                    </div>
                    @if(!$loop->last)
                        <hr>
                    @endif
                @endforeach
            </div>
        </div>
        {{-- Fin tarjeta--}}
    @else
        <p>{{ __('No skills assigned.') }}</p>
    @endif

    @include('partials.subtitulo', ['subtitulo' => __('Completed activities')])

    @if($unidades->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="thead-dark">
                <tr>
                    <th>{{ __('Unit') }}</th>
                    <th class="text-center">{{ __('Base') }}</th>
                    <th class="text-center">{{ __('Extra') }}</th>
                    <th class="text-center">Repaso</th>
                    <th class="text-center">{{ __('Exam') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($unidades as $unidad)
                    <tr>
                        <td class="align-middle">
                            @isset($unidad->codigo)
                                {{ $unidad->codigo }} -
                            @endisset
                            {{ $unidad->nombre }}
                        </td>
                        <td class="align-middle text-center {{ $unidad->num_actividades('base') > 0 ? $user->num_completadas('base', $unidad->id) < $unidad->num_actividades('base') ? 'bg-warning text-dark' : 'bg-success' : '' }}">
                            {{ $user->num_completadas('base', $unidad->id).'/'. $unidad->num_actividades('base') }}
                        </td>
                        <td class="align-middle text-center">
                            {{ $user->num_completadas('extra', $unidad->id) }}
                        </td>
                        <td class="align-middle text-center">
                            {{ $user->num_completadas('repaso', $unidad->id) }}
                        </td>
                        <td class="align-middle text-center">
                            {{ $user->num_completadas('examen', $unidad->id) }}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        @include('partials.subtitulo', ['subtitulo' => __('Content development')])

        {{-- Tarjeta --}}
        <div class="card">
            <div class="card-body">
                @foreach ($unidades as $unidad)
                    <h5 class="card-title">
                        @isset($unidad->codigo)
                            {{ $unidad->codigo }} -
                        @endisset
                        {{ $unidad->nombre }}</h5>
                    <p class="ml-5">{{ $unidad->descripcion }}</p>
                    <div class="ml-5 progress" style="height: 24px;">
                        @php($porcentaje = $resultados_unidades[$unidad->id]->actividad > 0 ? round($resultados_unidades[$unidad->id]->tarea/$resultados_unidades[$unidad->id]->actividad*100) : 0)
                        <div class="progress-bar {{ $porcentaje<50 ? 'bg-warning text-dark' : 'bg-success' }}"
                             role="progressbar"
                             style="width: {{ $porcentaje }}%;"
                             aria-valuenow="{{ $porcentaje }}"
                             aria-valuemin="0"
                             aria-valuemax="100">@if($porcentaje>0){{ $porcentaje }}%@endif
                        </div>
                    </div>
                    <div class="text-muted small text-right">
                        {{ $resultados_unidades[$unidad->id]->tarea + 0
                        }}/{{ $resultados_unidades[$unidad->id]->actividad + 0 }}
                    </div>
                    @if(!$loop->last)
                        <hr>
                    @endif
                @endforeach
            </div>
        </div>
        {{-- Fin tarjeta--}}

    @else
        <div class="row">
            <div class="col-md-12">
                <p>No hay unidades.</p>
            </div>
        </div>
    @endif
@endsection
