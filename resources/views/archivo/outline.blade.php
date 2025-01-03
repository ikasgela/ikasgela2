@extends('layouts.app')

@section('content')

    @include('partials.titular', ['titular' => __('Course progress')])

    @include('partials.tutorial', [
        'color' => 'success',
        'texto' => trans('tutorial.progreso')
    ])

    @if(Auth::user()->hasAnyRole(['profesor', 'tutor']))
        <div class="mb-3">
            {{ html()->form('POST', route('archivo.outline.filtro'))->open() }}
            @include('partials.desplegable_usuarios')
            {{ html()->form()->close() }}
        </div>
    @endif

    <div class="alert alert-warning">
        <div>
            <strong>{{ __('Start date') }}:</strong>
            <span>{{ $curso?->fecha_inicio ? $curso->fecha_inicio->isoFormat('L LT') : __('Undefined') }}</span>
        </div>
        <div>
            <strong>{{ __('End date') }}:</strong>
            <span>{{ $curso?->fecha_fin ? $curso->fecha_fin->isoFormat('L LT') : __('Undefined') }}</span>
        </div>
    </div>

    @if(count($unidades) > 0)

        @foreach($unidades as $unidad)

            @include('partials.subtitulo', ['subtitulo' => (isset($unidad->codigo) ? ($unidad->codigo.' - ') : '') . $unidad->nombre])

            <div class="container-fluid p-0">
                @if(!is_null($unidad->fecha_entrega) && $unidad->fecha_entrega > now())
                    @include('partials.tutorial', [
                        'color' => 'success',
                        'texto' => trans('tutorial.recomendacion')
                    ])
                    <hr class="mt-0">
                    <div class="row">
                        <div class="col-12 col-sm-3 col-md-2 col-lg-2 col-xl-2 col-xxl-1">
                            <span class="">{{ __('Recommended') }}</span>
                        </div>
                        <div class="col">
                            <div class="d-flex justify-content-between">
                                <div class="d-flex">
                                    <div>{{ !is_null($unidad->fecha_disponibilidad) ? $unidad->fecha_disponibilidad->isoFormat('L') : '-' }}</div>
                                    <div>
                                        &nbsp;{{ !is_null($unidad->fecha_disponibilidad) ? $unidad->fecha_disponibilidad->isoFormat('LT') : '' }}</div>
                                </div>
                                <div class="col text-muted small text-center">
                                    @include('partials.diferencia_fechas', ['fecha_inicial' => now(), 'fecha_final' => $unidad->fecha_entrega])
                                </div>
                                <div class="text-end">
                                    <div class="d-flex">
                                        <div>{{ !is_null($unidad->fecha_entrega) ? $unidad->fecha_entrega->isoFormat('L') : '-' }}</div>
                                        <div>
                                            &nbsp;{{ !is_null($unidad->fecha_entrega) ? $unidad->fecha_entrega->isoFormat('LT') : '' }}</div>
                                    </div>
                                </div>
                            </div>
                            @php($porcentaje = !is_null($unidad->fecha_disponibilidad) && !is_null($unidad->fecha_entrega) ? round(100-(now()->diffInSeconds($unidad->fecha_entrega)/$unidad->fecha_disponibilidad->diffInSeconds($unidad->fecha_entrega)*100),0) : 0)
                            <div class="progress-group-bars">
                                <div class="progress" style="height:24px">
                                    <div class="progress-bar bg-primary" role="progressbar"
                                         style="width: {{ $porcentaje > 0 ? $porcentaje : 0 }}%"
                                         aria-valuenow="{{ $porcentaje > 0 ? $porcentaje : 0 }}"
                                         aria-valuemin="0" aria-valuemax="100">
                                        {{ $porcentaje > 0 ? $porcentaje : 0 }}&thinsp;%
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-12 col-sm-3 col-md-2 col-lg-2 col-xl-2 col-xxl-1">
                            <span class="">{{ __('Actual') }}</span>
                        </div>
                        <div class="col">
                            <div class="progress m-0">
                                @php($porcentaje = $unidad->num_actividades('base') > 0 ? $user->num_completadas('base', $unidad->id)/$unidad->num_actividades('base')*100 : 0)
                                @php($minimo_entregadas = $unidad->minimo_entregadas ?? $curso->minimo_entregadas ?? 0)
                                <div
                                    class="progress-bar {{ $minimo_entregadas > 0 && $porcentaje < $minimo_entregadas ? 'bg-warning text-dark' : ($minimo_entregadas > 0 ? 'bg-success' : 'bg-primary') }}"
                                    role="progressbar"
                                    style="width: {{ $porcentaje }}%"
                                    aria-valuenow="{{ $porcentaje }}"
                                    title="{{ $user->num_completadas('base', $unidad->id).'/'. $unidad->num_actividades('base') }}"
                                    aria-valuemin="0" aria-valuemax="100">
                                    {{ formato_decimales($porcentaje) }}&thinsp;%
                                </div>
                            </div>
                            @if($minimo_entregadas > 0)
                                <div class="row g-0">
                                    <div class="col text-muted small" style="flex: 0 0 10%;">0&thinsp;%</div>
                                    <div class="col text-muted small text-end pe-1 border-end"
                                         style="flex: 0 0 {{ $minimo_entregadas-10 }}%;">
                                        {{ $minimo_entregadas }}&thinsp;%
                                    </div>
                                    <div class="col text-muted small text-end"
                                         style="flex: 0 0 {{ 100-$minimo_entregadas }}%;">100&thinsp;%
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <hr>
                @elseif(!is_null($unidad->fecha_entrega))
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="thead-dark">
                            <tr>
                                <th class="w-75">{{ __('Activity') }}</th>
                                <th>{{ __('Resources') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($unidad->actividades->sortBy('orden') as $actividad)
                                @if($actividad->plantilla && !$actividad->hasEtiqueta('extra') && !$actividad->hasEtiqueta('examen'))
                                    <tr class="table-row"
                                        data-href="{{ route('actividades.preview', $actividad->id) }}">
                                        <td class="align-middle">
                                            @include('actividades.partials.nombre_con_etiquetas')
                                        </td>
                                        <td class="align-middle">
                                            @include('partials.botones_recursos_readonly')
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p>{{ __('No dates defined yet.') }}</p>
                @endif
            </div>
        @endforeach

    @else
        <div class="row">
            <div class="col-md-12">
                <p>{{ __('No activities yet.') }}</p>
            </div>
        </div>
    @endif
@endsection
