<div class="alert alert-{{ $type }} alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <h5>
        @if ($type == 'danger')
        <i class="icon fas fa-ban"></i>
        @elseif ($type == 'warning')
        <i class="icon fas fa-exclamation-triangle"></i>
        @elseif ($type == 'info')
        <i class="icon fas fa-info"></i>
        @elseif ($type == 'success')
        <i class="icon fas fa-check"></i>
        @endif
        {{ $title }}
    </h5>
    {{ $slot }}
</div>