@php( $class = !$thread->alert && $thread->isUnread(Auth::id()) ? 'bg-success text-white' : '' )

<div class="card mb-3">
    <div class="card-header d-flex justify-content-between {{ $class }} {{ $thread->alert ? 'text-bg-warning' : '' }}">
        <span>
            @if(!$thread->alert)
                <i class="fas fa-comment"></i>
            @else
                <i class="fas fa-exclamation-triangle"></i>
            @endif
            <span class="ms-2">
                {{ $thread->creator()?->name ?: __('Unknown user') }} {{ $thread->creator()?->surname }}
            </span>
        </span>
        <span>{{ $thread->userUnreadMessagesCount(Auth::id()) }} {{ __('unread') }}</span>
    </div>
    <div class="card-body pb-1">
        <div class="d-flex justify-content-between">
            <h5 class="card-title"><a href="{{ route('messages.show', $thread->id) }}">{{ $thread->subject }}</a></h5>
            @auth
                @if(Auth::user()->hasRole('profesor'))
                    {!! Form::open(['route' => ['messages.destroy', $thread->id], 'method' => 'DELETE']) !!}
                    <div class="btn-group">
                        @include('partials.boton_borrar')
                    </div>
                    {!! Form::close() !!}
                @endif
            @endauth
        </div>
    </div>
    @if(!is_null($thread->latestMessage))
        <small class="text-secondary mx-3 mb-1">{{ __('Latest message') }}</small>
        <div class="text-body bg-light-subtle mx-3 mb-3 line-numbers">
            <div class="px-3 pt-3 overflow-auto border-start border-secondary-subtle border-4">
                {!! links_galeria($thread->latestMessage->body, $thread->id) !!}
            </div>
        </div>
        <small class="text-secondary mx-3 mb-3"
               title="{{ $thread->latestMessage->created_at->isoFormat('dddd, LL LTS') }}">
            {{ __('Posted') }} {{ $thread->latestMessage->created_at->diffForHumans() }}
        </small>
    @endif
</div>
