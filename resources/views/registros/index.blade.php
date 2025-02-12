@extends('layouts.app')

@section('content')

    @include('partials.titular', ['titular' => __('Records'), 'subtitulo' => ''])

    @if(Auth::user()->hasAnyRole(['admin']))
        <div class="mb-3">
            {{ html()->form('POST', route('registros_alumno.alumno'))->open() }}
            @include('partials.desplegable_usuarios')
            {{ html()->form()->close() }}
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="thead-dark">
            <tr>
                <th>#</th>
                <th>{{ __('User') }}</th>
                <th>{{ __('Task') }}</th>
                <th colspan="2">{{ __('Status') }}</th>
                <th>{{ __('Timestamp') }}</th>
                {{--                <th>{{ __('Actions') }}</th>--}}
            </tr>
            </thead>
            <tbody>
            @foreach($registros as $registro)
                <tr>
                    <td>{{ $registro->id }}</td>
                    <td>{{ $registro->user->name }} {{ $registro->user->surname }}</td>
                    <td>{{ $registro->tarea->actividad->full_name }}</td>
                    <td>{{ $registro->estado }}</td>
                    <td>
                        @switch($registro->estado)
                            @case(10)
                                {{-- Nueva --}}
                                Nueva
                                @break
                            @case(11)
                                {{-- Oculta --}}
                                Oculta
                                @break
                            @case(20)
                                {{-- Aceptada --}}
                                Aceptada
                                @break
                            @case(21)
                                {{-- Feedback leído --}}
                                Feedback leído
                                @break
                            @case(30)
                                {{-- Enviada --}}
                                Enviada
                                @break
                            @case(31)
                                {{-- Reiniciada --}}
                                Reiniciada
                                @break
                            @case(32)
                                {{-- Reabierta --}}
                                Reabierta
                                @break
                            @case(40)
                                {{-- Revisada: OK --}}
                                Revisada: OK
                                @break;
                            @case(41)
                                {{-- Revisada: ERROR --}}
                                Revisada: ERROR
                                @break;
                            @case(42)
                                {{-- Avance automático --}}
                                Avance automático
                                @break;
                            @case(50)
                                {{-- Terminada --}}
                                Terminada
                                @break
                            @case(60)
                                {{-- Archivada --}}
                                Archivada
                                @break
                            @case(61)
                                {{-- Borrada --}}
                                Borrada
                                @break
                            @case(62)
                                {{-- Caducada --}}
                                Caducada
                                @break
                            @case(63)
                                {{-- Reabierta --}}
                                Reabierta
                                @break
                            @case(64)
                                {{-- Avance automático y archivada --}}
                                Avance automático y archivada
                                @break
                            @case(70)
                                {{-- En pausa --}}
                                En pausa
                                @break
                            @case(71)
                                {{-- Mostrar siguiente --}}
                                Mostrar siguiente
                                @break
                            @default
                        @endswitch
                    </td>
                    <td>{{ Carbon\Carbon::parse($registro->timestamp)->isoFormat('L HH:mm:ss') }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    @include('partials.paginador', ['coleccion' => $registros])
@endsection
