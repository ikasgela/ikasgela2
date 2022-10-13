@extends('layouts.app')

@section('content')

    @include('partials.titular', ['titular' => __('Resources: Markdown texts')])

    @if(Auth::user()->hasAnyRole(['admin']))
        {!! Form::open(['route' => ['markdown_texts.index.filtro'], 'method' => 'POST']) !!}
        @include('partials.desplegable_cursos')
        {!! Form::close() !!}
    @endif

    <div class="mb-3">
        <a class="btn btn-primary" href="{{ route('markdown_texts.create') }}">{{ __('New markdown text') }}</a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="thead-dark">
            <tr>
                <th>#</th>
                <th>{{ __('Title') }}</th>
                <th>{{ __('Description') }}</th>
                <th>{{ __('Repository') }}</th>
                <th>{{ __('Branch') }}</th>
                <th>{{ __('File') }}</th>
                <th>{{ __('Actions') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($markdown_texts as $markdown_text)
                <tr>
                    <td>{{ $markdown_text->id }}</td>
                    <td>{{ $markdown_text->titulo }}</td>
                    <td>{{ $markdown_text->descripcion }}</td>
                    <td>{{ $markdown_text->repositorio }}</td>
                    <td>{{ $markdown_text->rama }}</td>
                    <td>
                        @if (config('ikasgela.gitea_enabled'))
                            <a target="_blank"
                               href="@include('markdown_texts.partials.enlace_readme')">{{ $markdown_text->archivo }}</a>
                        @endif
                    </td>
                    <td class="text-nowrap">
                        <div class='btn-group'>
                            @include('partials.boton_mostrar', ['ruta' => 'markdown_texts', 'recurso' => $markdown_text])
                            @include('partials.boton_editar', ['ruta' => 'markdown_texts', 'recurso' => $markdown_text])
                            {!! Form::open(['route' => ['markdown_texts.duplicar', $markdown_text->id], 'method' => 'POST']) !!}
                            @include('partials.boton_duplicar')
                            {!! Form::close() !!}
                            {!! Form::open(['route' => ['markdown_texts.destroy', $markdown_text->id], 'method' => 'DELETE']) !!}
                            @include('partials.boton_borrar')
                            {!! Form::close() !!}
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
