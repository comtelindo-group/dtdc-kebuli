@switch($status)
    @case('Tertarik dengan produk')
        <span class="badge badge-success">Tertarik dengan produk</span>
    @break

    @case('Hanya taruh brosur')
        <span class="badge badge-danger">Hanya taruh brosur</span>
    @break

    @case('Tidak tertarik')
        <span class="badge badge-warning">Tidak tertarik</span>
    @break

    @default
        <span class="badge badge-secondary">Unknown</span>
@endswitch
