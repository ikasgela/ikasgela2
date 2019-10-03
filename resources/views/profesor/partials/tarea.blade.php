@if(session('tutorial'))
    <div class="callout callout-success b-t-1 b-r-1 b-b-1">
        <small class="text-muted">{{ __('Tutorial') }}</small>
        <p>Aquí puedes valorar la actividad y dar el feedback oportuno.</p>
    </div>
@endif
@include('profesor.partials.tarjeta_usuario')
<div class="row mt-4">
    <div class="col-md-12">
        {{-- Tarjeta --}}
        <div class="card border-dark">
            <div class="card-header text-white bg-dark d-flex justify-content-between">
                <span>{{ $actividad->unidad->curso->nombre }} » {{ $actividad->unidad->nombre }}</span>
            </div>
            <form class="col-md-12 p-0"
                  method="POST"
                  action="{{ route('actividades.estado', [$tarea->id]) }}">
                @csrf
                @method('PUT')

                <div class="card-body">
                    <h2>{{ $actividad->nombre }}</h2>
                    <p>{{ $actividad->descripcion }}</p>
                    <div class="form-inline">
                        @if($tarea->estado == 11)
                            <button type="submit" name="nuevoestado" value="10"
                                    class="btn btn-secondary"> {{ __('Show') }}
                            </button>
                        @else
                            <button type="submit" name="nuevoestado" value="31"
                                    class="btn btn-secondary"> {{ __('Reset') }}
                            </button>
                        @endif
                        <label class="mx-3">{{ __('Attempts') }}: {{ $tarea->intentos }}</label>
                        <div class="form-inline">
                            <button type="submit" name="nuevoestado" value="41"
                                    class="mr-3 btn btn-warning"
                                    onclick="return validate_feedback();"> {{ __('Send again') }}
                            </button>
                            <label class="mr-2">{{ __('Score') }}</label>
                            <input class="mr-2 form-control" type="number" min="0" max="100" step="1"
                                   name="puntuacion"
                                   value="{{ !is_null($tarea->puntuacion) ? $tarea->puntuacion : $actividad->puntuacion }}"/>
                            <label class="mr-3"> {{ __('over') }} {{ $actividad->puntuacion }}</label>
                            <button type="submit" name="nuevoestado" value="40"
                                    class="btn btn-primary mr-3"
                                    onclick="return validate_feedback();"> {{ __('Finished') }}
                            </button>
                        </div>
                        @if(!is_null($actividad->siguiente))
                            <div class="form-inline">
                                <label>{{ __('Next') }}: @include('actividades.partials.siguiente')</label>
                                @if($actividad->final)
                                    <button type="submit" name="nuevoestado" value="70"
                                            class="mx-3 btn btn-light"> {{ __('Resume') }}
                                    </button>
                                @else
                                    @if($tarea->estado == 60)
                                        <button type="submit" name="nuevoestado" value="71"
                                                class="mx-3 btn btn-light"> {{ __('Show next') }}
                                        </button>
                                    @else
                                        <button type="submit" name="nuevoestado" value="70"
                                                class="mx-3 btn btn-light"> {{ __('Pause') }}
                                        </button>
                                    @endif
                                @endif
                            </div>
                        @endif()
                    </div>
                    <div class="mt-2">
                        <i class="fas fa-bullhorn mt-3"></i>
                        <label class="m-0" for="feedback">{{ __('Feedback') }}</label>
                    </div>
                    <div class="border rounded p-3">
                        <div class="form-group d-flex flex-row justify-content-between">
                            {!! Form::label('unidad', __('Message'), ['class' => 'col-form-label']) !!}
                            <div class="flex-fill mx-3">
                                <select class="form-control" id="feedback_id" name="feedback_id">
                                    {{--                                <option value="">{{ __('--- None ---') }}</option>--}}
                                    @foreach($feedbacks as $feedback)
                                        <option
                                            value="{{ $feedback->id }}" {{ session('profesor_feedback_actual') == $feedback->id ? 'selected' : '' }}>
                                            {{ $feedback->mensaje }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <button type="button" id="boton_feedback"
                                        class="btn btn-primary">{{ __('Add') }}</button>
                            </div>
                        </div>
                        <textarea class="form-control"
                                  id="feedback"
                                  name="feedback"
                                  rows="20">{{ !is_null($tarea->feedback) ? $tarea->feedback : '' }}
                            <p>=== {{ __('Comments').' (v'.($tarea->intentos+1).')' }} ===</p><br>
                        </textarea>
                    </div>
                </div>
            </form>
            @if($tarea->estado >= 10)
                <hr class="my-2">
                @include('partials.tarjetas_actividad')
            @endif
            {{-- Fin tarjeta--}}
        </div>
    </div>
</div>
