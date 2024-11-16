<div class="row mb-3">
    <div class="col-sm-2">
        {{ html()->label($label, $name ?? null)->class('col-form-label') }}
        {{ html()->hidden($name, $hidden ?? null) }}
    </div>
    <div class="col-sm-10">
        <span class="form-control-plaintext">{{ $value }}</span>
    </div>
</div>
