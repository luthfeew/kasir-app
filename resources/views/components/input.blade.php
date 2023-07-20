@if ($label)
<div class="form-group">
    <label for="{{ $name }}">{{ $label }}</label>
    <input type="{{ $type }}" name="{{ $name }}" id="{{ $name }}" class="form-control form-control-border border-width-2 @error($name) is-invalid @enderror" value="{{ old($name, $value) }}" placeholder="Masukkan {{ $label }}" {{ $attributes }}>
    @error($name)
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
</div>
@else
<input type="{{ $type }}" name="{{ $name }}" id="{{ $name }}" class="form-control form-control-border border-width-2 @error($name) is-invalid @enderror" value="{{ old($name, $value) }}" {{ $attributes }}>
@error($name)
<span class="invalid-feedback" role="alert">
    <strong>{{ $message }}</strong>
</span>
@enderror
@endif