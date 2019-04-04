<li class="nav-title">{{ __('Student') }}</li>
<li class="nav-item">
    <a class="nav-link" href="{{ route('users.home') }}">
        <i class="nav-icon fas fa-home"></i> {{ __('Desktop') }}
    </a>
</li>
@if(config('app.debug'))
    <li class="nav-item">
        <a class="nav-link" href="#">
            <i class="nav-icon fas fa-comment"></i> {{ __('Tutorship') }}
        </a>
    </li>
@endif
<li class="nav-item">
    <a class="nav-link" href="{{ route('actividades.archivo') }}">
        <i class="nav-icon fas fa-archive"></i> {{ __('Archived') }}
    </a>
</li>
@if(config('app.debug'))
    <li class="nav-item">
        <a class="nav-link" href="#">
            <i class="nav-icon fas fa-graduation-cap"></i> {{ __('Results') }}
        </a>
    </li>
@endif
