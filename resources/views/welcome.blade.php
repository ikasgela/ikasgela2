@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-12 col-lg-6 mx-auto">
            <div class="card">
                <div class="card-header">{{ __('Welcome') }}</div>
                <div class="card-body pb-1">
                    <p>{{ __('You need a user account to use ikasgela.') }}</p>
                </div>
                <hr class="mt-0 mb-0">
                <div class="card-body pb-3 text-center">
                    <a class="btn btn-primary me-3" href="{{ route('login') }}">{{ __('Sign in') }}</a>
                    @if(!is_null($current_organization) && $current_organization->isRegistrationOpen())
                        <span>{{ __('or') }}</span>
                        <a class="btn btn-link" href="{{ route('register') }}">{{ __('Sign up') }}</a>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection
