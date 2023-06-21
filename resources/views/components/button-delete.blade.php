<form action="{{ $link }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus item ini?')">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
</form>