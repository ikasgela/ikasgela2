@extends('layouts.app')

@section('content')

    @include('partials.titular', ['titular' => __('File resource')])

    <div class="row">
        <div class="col-md-6">
            @include('file_resources.tarjeta')
        </div>
    </div>

    @include('partials.backbutton')

@endsection
