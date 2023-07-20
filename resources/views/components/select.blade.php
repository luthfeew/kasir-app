<div class="form-group">
    <label>{{ $label }}</label>
    <select name="{{ $name }}" class="form-control select2 @error($name) is-invalid @enderror" style="width: 100%;">
        <option></option>
        @foreach ($options as $key => $value)
        @if ($selected)
            @if ($isSelected($key))
            <option value="{{ $key }}" selected>{{ $value }}</option>
            @else
            <option value="{{ $key }}">{{ $value }}</option>
            @endif
        @else
            @if (old($name) == $key)
            <option value="{{ $key }}" selected>{{ $value }}</option>
            @else
            <option value="{{ $key }}">{{ $value }}</option>
            @endif
        @endif
        @endforeach
    </select>
    @error($name)
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
</div>

@push('css')
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
@endpush

@push('js')
<script src="{{ asset('plugins/select2/js/select2.full.min.js')}}"></script>
<script>
    $(function() {
        //Initialize Select2 Elements
        $('.select2').select2({
            theme: 'bootstrap4',
            placeholder: "Pilih salah satu",
        })
    })
</script>
@endpush