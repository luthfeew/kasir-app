<div class="form-group">
    <label>{{ $label }}</label>
    <select name="{{ $name }}" class="form-control select2 @error($name) is-invalid @enderror" style="width: 100%;">
        <option></option>
        @foreach ($options as $option)
        <option value="{{ $option->id }}" {{ $isSelected(old($name, $option->id)) ? 'selected' : '' }}>
            {{ $option->nama }}
        </option>
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
            placeholder: "Pilih Kategori",
        })
    })
</script>
@endpush