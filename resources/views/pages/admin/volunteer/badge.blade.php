@switch($status)
    @case('memilih')
        <span class="badge badge-success">Memilih</span>
    @break

    @case('relawan')
        <span class="badge badge-info">Relawan</span>
    @break

    @case('ragu')
        <span class="badge badge-warning">Ragu</span>
    @break

    @case('tidak_memilih')
        <span class="badge badge-danger">Tidak memilih</span>
    @break

    @default
        <span class="badge badge-secondary">Unknown</span>
@endswitch
