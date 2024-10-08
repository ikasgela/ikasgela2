@if(count($actividades) > 0)
    @php($num_actividad = 1)
    @foreach($actividades as $actividad)
        @include('alumnos.partials.tarea')
        @php($num_actividad+=1)
    @endforeach
@else
    @include('partials.tutorial', [
        'color' => 'success',
        'texto' => trans('tutorial.enviadas')
    ])
    <div class="row">
        <div class="col-md-12">
            <p>{{ $mensaje_ninguna }}</p>
        </div>
    </div>
@endif
