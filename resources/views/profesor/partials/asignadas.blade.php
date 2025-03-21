@include('partials.subtitulo', ['subtitulo' => __('Assigned activities')])

@include('profesor.partials.selector_unidad',['nombre_variable' => 'unidad_id_asignadas'])

@if($actividades->count() > 0 )
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="thead-dark">
            <tr>
                <th class="p-0"></th>
                <th>
                    <input type="checkbox" id="seleccionar_asignadas">
                </th>
                <th>#</th>
                <th>{{ __('Activity') }}</th>
                <th class="text-center">
                    @include('profesor.partials.titulo-columna', ['titulo' => trans_choice('tasks.hidden', 1)])
                </th>
                <th class="text-center">
                    @include('profesor.partials.titulo-columna', ['titulo' => trans_choice('tasks.accepted', 1)])
                </th>
                <th class="text-center">
                    @include('profesor.partials.titulo-columna', ['titulo' => trans_choice('tasks.sent', 1)])
                </th>
                <th class="text-center">
                    @include('profesor.partials.titulo-columna', ['titulo' => trans_choice('tasks.reviewed', 1)])
                </th>
                <th class="text-center">
                    @include('profesor.partials.titulo-columna', ['titulo' => __('Score')])
                </th>
                <th class="text-center">
                    @include('profesor.partials.titulo-columna', ['titulo' => trans_choice('tasks.finished', 1)])
                </th>
                <th class="text-center">
                    @include('profesor.partials.titulo-columna', ['titulo' => trans_choice('tasks.expired', 1)])
                </th>
                <th>{{ __('Next') }}</th>
                @if(Auth::user()->hasRole('admin'))
                    <th>{{ __('Resources') }}</th>
                @endif
                <th>{{ __('Actions') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($actividades as $actividad)
                <tr class="table-cell-click"
                    data-href="{{ route('profesor.revisar', ['user' => $user->id, 'tarea' => $actividad->tarea->id]) }}">
                    <td class="p-0 ps-1 {{ $actividad->tarea->estado == 30 && !$actividad->auto_avance ? 'bg-danger' : '' }}">
                        &nbsp;
                    </td>
                    <td>
                        <input form="multiple"
                               data-chkbox-shiftsel="grupo3"
                               type="checkbox" name="asignadas[]" value="{{ $actividad->tarea->id }}">
                    </td>
                    <td class="clickable">{{ $actividad->tarea->id }}</td>
                    <td class="clickable">
                        @include('actividades.partials.nombre_con_etiquetas', ['ruta' => explode('.', Route::currentRouteName())[0] . '.tareas.filtro', 'slug' => true])
                    </td>
                    <td class="text-center clickable">{!! $actividad->tarea->estado == 11 ? '<i class="fas fa-check"></i>' : '<i class="fas fa-times text-secondary"></i>' !!}</td>
                    <td class="text-center clickable">{!! $actividad->tarea->estado >= 20 ? '<i class="fas fa-check"></i>' : '<i class="fas fa-times text-danger"></i>' !!}</td>
                    <td class="text-center clickable {!! $actividad->tarea->estado == 30 && !$actividad->auto_avance ? 'bg-danger text-white' : '' !!}">
                        {!! $actividad->tarea->estado >= 30 ? '<i class="fas fa-check"></i>' : '<i class="fas fa-times text-danger"></i>' !!}
                    </td>
                    <td class="text-center clickable">
                        @switch($actividad->tarea->estado)
                            @case(40)
                                {!! '<i class="fas fa-check text-success"></i>' !!}
                                @break
                            @case(41)
                                {!! '<i class="fas fa-check text-warning"></i>' !!}
                                @break
                            @case(50)
                            @case(60)
                                {!! '<i class="fas fa-check"></i>' !!}
                                @break;
                            @default
                                @if(!$actividad->auto_avance)
                                    {!! '<i class="fas fa-times text-danger"></i>' !!}
                                @else
                                    {!! '<i class="fas fa-times text-secondary"></i>' !!}
                                @endif
                        @endswitch
                    </td>
                    <td class="text-center clickable">{{ $actividad->tarea->puntuacion }}</td>
                    <td class="text-center clickable">{!! $actividad->tarea->estado >= 50 ? '<i class="fas fa-check"></i>' : '<i class="fas fa-times text-danger"></i>' !!}</td>
                    <td class="text-center clickable">
                        <div class="d-flex justify-content-center align-items-center">
                            {!! $actividad->is_expired ? (!$actividad->tarea->is_completada ? '' : ($actividad->tarea->is_completada_archivada ? '<i class="fas fa-exclamation-triangle text-secondary"></i>' : '<i class="fas fa-times text-secondary"></i>')) : '<i class="fas fa-times text-secondary"></i>' !!}
                            @if($actividad->is_expired && !$actividad->tarea->is_completada)
                                {{ html()->form('PUT', route('actividades.estado', $actividad->tarea->id))->open() }}
                                <div class='btn-group'>
                                    <button type="submit" name="nuevoestado" value="63"
                                            title="{{ __('Extend deadline') }}"
                                            class="btn btn-sm text-bg-warning">
                                        +{{ $actividad->unidad->curso->plazo_actividad ?? 7 }}</button>
                                </div>
                                <input type="hidden" name="ampliacion_plazo"
                                       value="{{ $actividad->unidad->curso->plazo_actividad ?? 7 }}"/>
                                {{ html()->form()->close() }}
                            @endif
                        </div>
                    </td>
                    @include('profesor.partials.siguiente_actividad')
                    @if(Auth::user()->hasRole('admin'))
                        @include('partials.botones_recursos')
                    @endif
                    <td>
                        <form method="POST"
                              action="{{ route('tareas.destroy', ['user' => $user->id, 'tarea' => $actividad->tarea->id]) }}">
                            @csrf
                            @method('DELETE')
                            <div class='btn-group'>
                                <a title="{{ __('Review') }}"
                                   href="{{ route('profesor.revisar', ['user' => $user->id, 'tarea' => $actividad->tarea->id]) }}"
                                   class="btn btn-light btn-sm"><i class="fas fa-bullhorn"></i></a>
                                @if(Auth::user()->hasRole('admin'))
                                    <a title="{{ __('Edit activity') }}"
                                       href="{{ route('actividades.edit', [$actividad->id]) }}"
                                       class='btn btn-light btn-sm'><i class="fas fa-edit"></i></a>
                                    <a title="{{ __('Edit task') }}"
                                       href="{{ route('tareas.edit', [$actividad->tarea->id]) }}"
                                       class='btn btn-light btn-sm'><i class="fas fa-link"></i></a>
                                @endif
                                @include('partials.boton_borrar')
                            </div>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
            <tfoot class="thead-dark">
            <tr>
                <th colspan="6"></th>
                @if(session('profesor_filtro_actividades_examen') == 'E')
                    <th class="text-center">{{ $user->num_actividades_enviadas_noautoavance() > 0 ? $user->num_actividades_enviadas_noautoavance() : '0' }}</th>
                @else
                    <th class="text-center">{{ $user->num_actividades_enviadas_noautoavance_noexamen() > 0 ? $user->num_actividades_enviadas_noautoavance_noexamen() : '0' }}</th>
                @endif
                <th colspan="7"></th>
            </tr>
            <tr>
                <td colspan="6">
                    <div class="form-inline">
                        {{ html()->form('DELETE', route('tareas.borrar_multiple', $user->id))->id('multiple')->open() }}
                        <span>{{ __('With the selected') }}: </span>
                        @include('partials.boton_borrar')
                        {{ html()->form()->close() }}
                    </div>
                </td>
            </tr>
            </tfoot>
        </table>
    </div>

    <div class="d-flex justify-content-center">
        {{ $actividades->appends(['disponibles' => $disponibles->currentPage()])->links() }}
    </div>
@else
    <div class="mb-3">
        <p>{{ __("The user doesn't have any assigned activities.") }}</p>
    </div>
@endif
