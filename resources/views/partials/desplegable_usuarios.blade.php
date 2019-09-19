<div class="form-group d-flex flex-row justify-content-between">
    {!! Form::label('user', __('User'), ['class' => 'col-form-label']) !!}
    <div class="flex-fill mx-3">
        <select class="form-control" id="user_id" name="user_id">
            <option value="">{{ __('--- None ---') }}</option>
            @foreach($users as $user)
                <option
                    value="{{ $user->id }}" {{ session('filtrar_user_actual') == $user->id ? 'selected' : '' }}>
                    {{ $user->name }}
                </option>
            @endforeach
        </select>
    </div>
    <div>
        <button type="submit" class="btn btn-primary">{{ __('Filter') }}</button>
    </div>
</div>