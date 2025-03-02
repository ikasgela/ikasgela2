@section('fancybox')
    <link rel="stylesheet" href="{{ asset('/js/jquery.fancybox.min.css') }}"/>
    <script src="{{ asset('/js/jquery.fancybox.min.js') }}" defer></script>
@endsection

<div class="card mb-3">
    <div class="card-header d-flex justify-content-between">
        <div><i class="fas fa-file-upload me-2"></i>{{ __('Image upload') }}</div>
        <div>
            @include('partials.modificar_recursos', ['ruta' => 'file_uploads'])
            @include('partials.editar_recurso', ['recurso' => $file_upload, 'ruta' => 'file_uploads'])
        </div>
    </div>
    <div class="card-body">
        @include('partials.cabecera_recurso', ['recurso' => $file_upload, 'ruta' => 'file_uploads'])
        @if(count($file_upload->files) > 0)
            <div class="table-responsive">
                <table class="table table-bordered small m-0">
                    <thead class="thead-dark">
                    <tr>
                        <th>{{ __('File') }}</th>
                        <th>{{ __('Size') }}</th>
                        <th>{{ __('Uploaded') }}</th>
                        @if(Route::currentRouteName() != 'archivo.show')
                            <th class="text-center">{{ __('Actions') }}</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($file_upload->files as $file)
                        <tr>
                            <td>
                                <a data-fancybox="gallery_{{ $file_upload->id }}"
                                   href="{{ $file->imageUrl('images') }}">
                                    <img style="width:64px" src="{{ $file->imageUrl('thumbnails') }}"
                                         loading="lazy"
                                         alt="{{ $file->title }}" title="{{ $file->title }}"
                                         onerror="this.onerror=null;this.src='{{ url("/svg/missing_image.svg") }}';">
                                </a>
                            </td>
                            <td>{{ $file->size_in_kb }} KB</td>
                            <td>{{ $file->uploaded_time }}</td>
                            @if(Route::currentRouteName() != 'archivo.show')
                                <td class="text-center">
                                    <div class='btn-group'>
                                        {{ html()->form('POST', route('files.rotate_left', $file->id))->open() }}
                                        <button title="{{ __('Rotate left') }}"
                                                type="submit" class="btn btn-light btn-sm me-1">
                                            <i class="fas fa-undo"></i>
                                        </button>
                                        {{ html()->form()->close() }}
                                        {{ html()->form('POST', route('files.rotate_right', $file->id))->open() }}
                                        <button title="{{ __('Rotate right') }}"
                                                type="submit" class="btn btn-light btn-sm me-1">
                                            <i class="fas fa-undo fa-flip-horizontal"></i>
                                        </button>
                                        {{ html()->form()->close() }}
                                        @if(!$file->archived || Auth::user()->hasAnyRole(['admin','profesor']))
                                            {{ html()->form('DELETE', route('files.delete', $file->id))->open() }}
                                            @include('partials.boton_borrar')
                                            {{ html()->form()->close() }}
                                        @endif
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
    @use('Illuminate\Support\Str')
    @if(count($file_upload->not_archived_files) < $file_upload->max_files && Route::currentRouteName() != 'archivo.show' && Route::currentRouteName() != 'actividades.preview' || !Auth::user()->hasRole('alumno'))
        <hr class="my-0">
        <div class="card-body">
            <p class="small">{{ __('Upload limit') }}:
                {{ $file_upload->max_files-count($file_upload->not_archived_files) }} {{ Str::lower(__('Files')) }}.</p>
            <form action="{{ route('files.upload.image') }}" enctype="multipart/form-data" method="post">
                @csrf
                <div class="form-group mb-3">
                    <input class="form-control" type="file" name="file" id="file">
                    <input type="hidden" name="file_upload_id" value="{{ $file_upload->id }}">
                    <span class="help-block text-danger">{{ $errors->first('file') }}</span>
                </div>
                <button class="btn btn-primary single_click">
                    <i class="fas fa-spinner fa-spin" style="display:none;"></i> {{ __('Upload') }}</button>
            </form>
        </div>
    @endif
</div>
