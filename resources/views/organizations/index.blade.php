@extends('layouts.app')

@section('content')

    @include('partials.titular', ['titular' => __('Organizations'), 'subtitulo' => ''])

    <div class="mb-3">
        <a class="btn btn-primary" href="{{ route('organizations.create') }}">{{ __('New organization') }}</a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="thead-dark">
            <tr>
                <th>#</th>
                <th>{{ __('Name') }}</th>
                <th>{{ __('Slug') }}</th>
                <th class="text-center">{{ __('Registration open') }}</th>
                <th class="text-center">{{ __('Available seats') }}</th>
                <th class="text-center">{{ __('Current period') }}</th>
                <th>{{ __('Actions') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($organizations as $organization)
                <tr>
                    <td>{{ $organization->id }}</td>
                    <td>{{ $organization->name }}</td>
                    <td>{{ $organization->slug }}</td>
                    <td class="text-center {{ $organization->registration_open ? 'text-bg-warning' : '' }}">
                        {{ $organization->registration_open ? __('Yes') : __('No') }}
                    </td>
                    <td class="text-center">{{ $organization->seats }}</td>
                    <td class="text-center">{{ $organization->current_period()->name ?? '' }}</td>
                    <td>
                        {{ html()->form('DELETE', route('organizations.destroy', $organization->id))->open() }}
                        <div class='btn-group'>
                            <a title="{{ __('Edit') }}"
                               href="{{ route('organizations.edit', [$organization->id]) }}"
                               class='btn btn-light btn-sm'><i class="fas fa-edit"></i></a>
                            @include('partials.boton_borrar')
                        </div>
                        {{ html()->form()->close() }}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
