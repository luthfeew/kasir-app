<div class="input-group mb-3">
    <input type="{{ $type }}" name="{{ $name }}" id="{{ $name }}" class="form-control @error($name) is-invalid @enderror" value="{{ old($name, $value) }}" {{ $attributes }}>
    <div class="input-group-append">
        <div class="input-group-text">
            @if ($name == 'password')
            <span class="fas fa-lock"></span>
            @else
            <span class="fas fa-user"></span>
            @endif
        </div>
    </div>
    @error($name)
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
</div>