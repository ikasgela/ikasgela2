@switch($actividad->tarea->estado)
    @case(10)   {{-- Nueva --}}
    @include('partials.tutorial', [
        'color' => 'c-callout-success',
        'texto' => trans('tutorial.para_comenzar')
    ])
    @break
    @case(20)   {{-- Aceptada --}}
    @case(21)   {{-- Feedback leído --}}
    @include('partials.tutorial', [
        'color' => 'c-callout-success',
        'texto' => trans('tutorial.completa_envia')
    ])
    @break
    @case(30)   {{-- Enviada --}}
    @include('partials.tutorial', [
        'color' => 'c-callout-success',
        'texto' => trans('tutorial.pendiente_revisar')
    ])
    @break
    @case(40)   {{-- Revisada: OK --}}
    @case(41)   {{-- Revisada: ERROR --}}
    @include('partials.tutorial', [
        'color' => 'c-callout-success',
        'texto' => trans('tutorial.revisada', ['url' => route('archivo.index')])
    ])
    @break
    @case(42)   {{-- Avance automático --}}
    @case(50)   {{-- Terminada --}}
    @include('partials.tutorial', [
        'color' => 'c-callout-success',
        'texto' => trans('tutorial.terminada', ['url' => route('archivo.index')])
    ])
    @break
    @case(60)   {{-- Archivada --}}
    @break
    @default
@endswitch

@if(Route::current()->getName() == 'archivo.show')
    @include('partials.tutorial', [
        'color' => 'c-callout-success',
        'texto' => trans('tutorial.archivada')
    ])
@endif

@section('fancybox')
    <link rel="stylesheet" href="{{ asset('/js/jquery.fancybox.min.css') }}"/>
    <script src="{{ asset('/js/jquery.fancybox.min.js') }}" defer></script>
@endsection

<div class="row">
    <div class="col-md-12">
        {{-- Tarjeta --}}
        <div class="card border-dark">
            <div class="card-header text-white bg-dark d-flex justify-content-between">
                <span>{{ $actividad->unidad->curso->nombre }} » {{ $actividad->unidad->nombre }}</span>
                @if(isset($actividad->fecha_entrega) && !$actividad->tarea->is_completada && !$actividad->tarea->is_enviada)
                    @if(!$actividad->is_finished)
                        <div>{{ __('Remaining time') }}:
                            <span data-countdown="{{ $actividad->fecha_entrega }}"></span>
                        </div>
                    @else
                        <span>
                            <i class="fas fa-exclamation-triangle text-warning"></i>
                            {{ __('Task expired') }}
                            <i class="fas fa-exclamation-triangle text-warning"></i>
                        </span>
                    @endif
                @endif
                @if(isset($num_actividad))
                    <span>{{ $num_actividad }} {{ __('of') }} {{count($actividades)}}</span>
                @endif
            </div>
            <div class="card-body pb-1">
                <div class="d-flex flex-row flex-wrap justify-content-between align-items-baseline mb-3">
                    <div>
                        @include('actividades.partials.encabezado_con_etiquetas')
                        <p>{{ $actividad->descripcion }}</p>
                    </div>
                    @if(Auth::user()->hasRole('alumno') && !$actividad->hasEtiqueta('examen'))
                        @include('actividades.partials.boton_pregunta')
                    @elseif($actividad->hasEtiqueta('examen'))
                        @include('actividades.partials.puntuacion_examen')
                    @endif
                </div>
                <div class="mb-3">
                    <form method="POST"
                          action="{{ route('actividades.estado', [$actividad->tarea->id]) }}">
                        @csrf
                        @method('PUT')
                        @switch($actividad->tarea->estado)
                            @case(10)   {{-- Nueva --}}
                            @if(!$actividad->is_finished) {{-- Mostrar si no ha superado la fecha de entrega --}}
                            <button type="submit" name="nuevoestado" value="20"
                                    class="btn btn-primary single_click">
                                <i class="fas fa-spinner fa-spin" style="display:none;"></i>
                                {{ __('Accept activity') }}
                            </button>
                            @endif
                            @break
                            @case(20)   {{-- Aceptada --}}
                            @case(21)   {{-- Feedback leído --}}
                            @if($actividad->envioPermitido())
                                @if($actividad->unidad->curso->disponible() || $actividad->hasEtiqueta('examen'))
                                    @if(!$actividad->is_expired) {{-- Mostrar el botón si no ha superado el límite --}}
                                    <button type="submit" name="nuevoestado" value="30"
                                            @if(!$actividad->auto_avance)
                                            onclick="return confirm('{{ __('Are you sure?') }}\n\n{{ __('This will submit the activity for review and show the next one if available.') }}')"
                                            @endif
                                            class="btn btn-primary mr-2 single_click">
                                        <i class="fas fa-spinner fa-spin"
                                           style="display:none;"></i> {{ __('Submit for review') }}</button>
                                    @endif
                                @else
                                    <div class="alert alert-danger pb-0" role="alert">
                                        <p>El curso ha finalizado, no se admiten más envíos.</p>
                                    </div>
                                @endif
                            @endif
                            @if($actividad->hasEtiqueta('extra') && !is_null($actividad->siguiente))
                                <button type="submit" name="nuevoestado" value="71"
                                        class="btn btn-light single_click">
                                    <i class="fas fa-spinner fa-spin" style="display:none;"></i> {{ __('Show next') }}
                                </button>
                            @endif()
                            @break
                            @case(30)   {{-- Enviada --}}
                            @if($actividad->auto_avance)
                                <div class="alert alert-success" role="alert">
                                    <p>{{ __('This is an automatically advancing activity, there is no teacher review.') }}</p>
                                    <button type="submit" name="nuevoestado" value="42"
                                            class="btn btn-success single_click">
                                        <i class="fas fa-spinner fa-spin"
                                           style="display:none;"></i> {{ __('Next step') }}
                                    </button>
                                </div>
                            @elseif(!$actividad->is_finished)
                                <button type="submit" name="nuevoestado" value="32"
                                        @if(!$actividad->hasEtiqueta('examen'))
                                        onclick="return confirm('{{ __('Are you sure?') }}\n\n{{ __('Reopening the activity cancels the submission and allows making corrections, but it has a 5 point penalty.') }}')"
                                        @endif
                                        class="btn btn-secondary single_click">
                                    <i class="fas fa-spinner fa-spin"
                                       style="display:none;"></i> {{ __('Reopen activity') }}</button>
                            @endif
                            @if(config('app.debug'))
                                <button type="submit" name="nuevoestado" value="40"
                                        class="btn btn-success ml-3"> {{ __('Ok') }}
                                </button>
                                <button type="submit" name="nuevoestado" value="41"
                                        class="btn btn-danger"> {{ __('Error') }}
                                </button>
                            @endif
                            @break
                            @case(40)   {{-- Revisada: OK --}}
                            @case(42)   {{-- Avance automático --}}
                            <button type="submit" name="nuevoestado" value="60"
                                    class="btn btn-primary single_click">
                                <i class="fas fa-spinner fa-spin" style="display:none;"></i> {{ __('Archive') }}
                            </button>
                            @break;
                            @case(41)   {{-- Revisada: ERROR --}}
                            <button type="submit" name="nuevoestado" value="21"
                                    class="btn btn-primary single_click">
                                <i class="fas fa-spinner fa-spin" style="display:none;"></i> {{ __('Feedback read') }}
                            </button>
                            @break
                            @case(50)   {{-- Terminada --}}
                            <button type="submit" name="nuevoestado" value="60"
                                    class="btn btn-primary single_click">
                                <i class="fas fa-spinner fa-spin" style="display:none;"></i> {{ __('Archive') }}
                            </button>
                            @break
                            @case(60)   {{-- Archivada --}}
                            @break
                            @default
                        @endswitch
                    </form>
                </div>
            </div>
            @switch($actividad->tarea->estado)
                @case(10)   {{-- Nueva --}}
                @case(20)   {{-- Aceptada --}}
                @case(21)   {{-- Feedback leído --}}
                @case(30)   {{-- Enviada --}}
                @case(40)   {{-- Revisada: OK --}}
                @case(41)   {{-- Revisada: ERROR --}}
                @case(50)   {{-- Terminada --}}
                <hr class="mt-0 mb-2">
                @break
                @case(60)   {{-- Archivada --}}
                @break
                @default
            @endswitch
            <div class="card-body py-1">
                <h6 class="text-center font-weight-bold mt-2">
                    @switch($actividad->tarea->estado)
                        @case(10)   {{-- Nueva --}}
                        {{ __('Not yet accepted') }}
                        @break
                        @case(20)   {{-- Aceptada --}}
                        @case(21)   {{-- Feedback leído --}}
                        {{ __('Preparing for submission') }}
                        @break
                        @case(30)   {{-- Enviada --}}
                        {{ __('Waiting for review') }}
                        @break
                        @case(40)   {{-- Revisada: OK --}}
                        @case(41)   {{-- Revisada: ERROR --}}
                        {{ __('Review complete') }}
                        @break
                        @case(50)   {{-- Terminada --}}
                        {{ __('Finished') }}
                        @break
                        @case(60)   {{-- Archivada --}}
                        @break
                        @default
                    @endswitch
                </h6>
                <ul class="progress-indicator">
                    @switch($actividad->tarea->estado)
                        @case(10)   {{-- Nueva --}}
                        <li><span class="bubble"></span>{{ __('Accepted') }}</li>
                        <li><span class="bubble"></span>{{ __('Submitted') }}</li>
                        <li><span class="bubble"></span>{{ __('Feedback available') }}</li>
                        <li><span class="bubble"></span>{{ __('Finished') }}</li>
                        @break
                        @case(20)   {{-- Aceptada --}}
                        @case(21)   {{-- Feedback leído --}}
                        <li class="completed"><span class="bubble"></span>{{ __('Accepted') }}</li>
                        <li><span class="bubble"></span>{{ __('Submitted') }}</li>
                        <li><span class="bubble"></span>{{ __('Feedback available') }}</li>
                        <li><span class="bubble"></span>{{ __('Finished') }}</li>
                        @break
                        @case(30)   {{-- Enviada --}}
                        <li class="completed"><span class="bubble"></span>{{ __('Accepted') }}</li>
                        <li class="completed"><span class="bubble"></span>{{ __('Submitted') }}</li>
                        <li><span class="bubble"></span>{{ __('Feedback available') }}</li>
                        <li><span class="bubble"></span>{{ __('Finished') }}</li>
                        @break
                        @case(40)   {{-- Revisada: OK --}}
                        @case(41)   {{-- Revisada: ERROR --}}
                        <li class="completed"><span class="bubble"></span>{{ __('Accepted') }}</li>
                        <li class="completed"><span class="bubble"></span>{{ __('Submitted') }}</li>
                        <li class="completed"><span class="bubble"></span>{{ __('Feedback available') }}
                        </li>
                        <li><span class="bubble"></span>{{ __('Finished') }}</li>
                        @break
                        @case(42)   {{-- Avance automático --}}
                        @case(50)   {{-- Terminada --}}
                        <li class="completed"><span class="bubble"></span>{{ __('Accepted') }}</li>
                        <li class="completed"><span class="bubble"></span>{{ __('Submitted') }}</li>
                        <li class="completed"><span class="bubble"></span>{{ __('Feedback available') }}
                        </li>
                        <li class="completed"><span class="bubble"></span>{{ __('Finished') }}</li>
                        @break
                        @case(60)   {{-- Archivada --}}
                        @break
                        @default
                    @endswitch
                </ul>
            </div>
            @switch($actividad->tarea->estado)
                @case(20)   {{-- Aceptada --}}
                @case(21)   {{-- Feedback leído --}}
                @if(!$actividad->is_expired)
                    <hr class="mt-0 mb-2">
                    @include('partials.tarjetas_actividad')
                @else
                    <div class="mb-2"></div>
                @endif
                @break
                @case(60)   {{-- Archivada --}}
                <hr class="mt-0 mb-2">
                @include('partials.tarjetas_actividad')
                @break
                @default
                <div class="mb-2"></div>
            @endswitch
            @if($actividad->tarea->estado > 10 && $actividad->tarea->estado != 30)
                @if(!is_null($actividad->tarea->feedback))
                    <hr class="mt-0 mb-2">
                    <div class="row mt-3 mb-0 mx-2">
                        <div class="col-md-12">
                            <div class="card
                            {{ $actividad->unidad->curso->disponible() && $actividad->tarea->estado == 40 ? !$actividad->hasEtiqueta('examen') ? 'border-success' : 'border-secondary' : '' }}
                            {{ $actividad->unidad->curso->disponible() && $actividad->tarea->estado == 41 ? 'border-warning' : '' }}
                            {{ !$actividad->unidad->curso->disponible() ? 'border-secondary' : '' }}">
                                <div class="card-header
                                {{ $actividad->unidad->curso->disponible() && $actividad->tarea->estado == 40 ? !$actividad->hasEtiqueta('examen') ? 'bg-success' : 'bg-secondary' : '' }}
                                {{ $actividad->unidad->curso->disponible() && $actividad->tarea->estado == 41 ? 'bg-warning text-dark' : '' }}
                                {{ !$actividad->unidad->curso->disponible() ? 'bg-secondary' : '' }}">
                                    <i class="fas fa-bullhorn"></i> {{ __('Feedback') }}
                                </div>
                                <div class="mx-3 mt-3 p-1">
                                    <div class="media rounded line-numbers">
                                        <div class="media-body overflow-auto">
                                            {!! links_galeria($actividad->tarea->feedback) !!}
                                        </div>
                                    </div>
                                    <hr class="mt-0 mb-2">
                                    <p class="text-muted small">
                                        {{ __('Score') }}: @include('actividades.partials.puntuacion')
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endif
        </div>
        {{-- Fin tarjeta--}}
    </div>
</div>
