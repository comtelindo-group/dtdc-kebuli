@extends('layouts.app')

@section('page-title', 'Volunteer')
@section('page-subtitle', 'List')

@section('navbar')
    @include('layouts.navbar')
@endsection


@section('sidebar')
    @include('layouts.sidebar')
@endsection

@section('content')
    <div class="bg-white shadow p-8 rounded">
        <div class="mb-4 d-flex flex-column flex-md-row justify-content-between align-items-center">
            <h4 class="fw-bolder">List Volunteer</h4>
            <div class="d-flex gap-2">
                <div class="d-flex align-items-center gap-2 gap-lg-4">
                    <a href="{{ route('admin.volunteer.map') }}" class="btn btn-flex btn-info btn-sm">
                        <i class="fas fa-map fs-4 pe-2"></i>Pemetaan</a>
                </div>
                <div class="d-flex align-items-center gap-2 gap-lg-4">
                    <a href="{{ route('admin.volunteer.export') }}" class="btn btn-flex btn-success btn-sm">
                        <i class="fas fa-file-excel fs-4 pe-2"></i>Export</a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 mb-4">
                <select name="user_id" class="form-select form-select-solid" id="user" data-control="select2">
                    <option value="*" selected>Semua SURVEYOR</option>
                    @foreach ($users as $data)
                        <option value="{{ $data->id }}">{{ $data->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-4 mb-4">
                <select name="status" class="form-select form-select-solid" id="user" data-control="select2">
                    <option value="*" selected>Semua Status</option>
                    <option value="relawan">Relawan</option>
                    <option value="memilih">Memilih</option>
                    <option value="ragu">Ragu</option>
                    <option value="tidak_memilih">Tidak memilih</option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 mb-4">
                <select name="kelurahan[]" multiple class="form-select form-select-solid" id="kelurahan"
                    data-control="select2" data-placeholder="Semua Kelurahan">
                    @foreach ($kelurahan as $a)
                        <option value="{{ $a }}">{{ $a }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-4 mb-4">
                <button class="btn btn-sm btn-primary" type="button" id="btn-search">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
        <table class="table align-top table-striped border table-rounded gy-5 dataTable no-footer" id="table"
            aria-describedby="kt_table_jobs_info" style="width: 1202px;">
            <thead>
                <tr class="fw-bold fs-7 text-gray-500 text-uppercase border-bottom-2">
                    <th class="w-50px text-center">NO</th>
                    <th>SURVEYOR</th>
                    <th>NAMA</th>
                    <th>NOMOR HP</th>
                    <th>KELURAHAN</th>
                    <th>RT</th>
                    <th>STATUS</th>
                    <th>TANGGAL</th>
                    <th>#</th>
                </tr>
            </thead>
            <tbody class="fs-7">
            </tbody>
        </table>
    </div>

    <script>
        const onDelete = (id) => {
            Swal.fire({
                title: 'Delete!',
                text: `Apakah Anda yakin ingin menghapus?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: 'rgb(221, 107, 85)',
                cancelButtonColor: 'gray',
                confirmButtonText: 'Yes, Delete!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('admin.volunteer.delete') }}",
                        type: 'POST',
                        data: {
                            id
                        },
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success: function(data) {
                            Swal.fire({
                                title: 'Success',
                                text: `${data.message}`,
                                icon: 'success',
                                confirmButtonColor: 'green',
                            });

                            $('#table').DataTable().ajax.reload();
                        },
                        error: function(xhr, status, error) {
                            const data = xhr.responseJSON;
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: data.message,
                            });
                        }
                    });
                }
            });
        };

        $(document).ready(function() {
            $('[data-control="select2"]').val('*').trigger('change');

            $('#table').DataTable({
                processing: true,
                serverSide: true,
                retrieve: true,
                deferRender: true,
                // responsive: false,
                ajax: {
                    url: "{{ route('admin.volunteer.table') }}",
                    data: function(d) {
                        d.user_id = $('[name="user_id"]').val();
                        d.status = $('[name="status"]').val();
                        d.kelurahan = $('[name="kelurahan[]"]').val();
                    }
                },
                language: {
                    "emptyTable": "Tidak ada data terbaru 📁",
                    "zeroRecords": "Data tidak ditemukan 😞",
                },
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'user',
                        orderable: false,
                        searchable: true
                    },
                    {
                        data: 'name',
                        orderable: false,
                        searchable: true
                    },
                    {
                        data: 'phone_number',
                        orderable: false,
                        searchable: true
                    },
                    {
                        data: 'kelurahan',
                        orderable: false,
                        searchable: true
                    },
                    {
                        data: 'rt',
                        orderable: false,
                        searchable: true
                    },
                    {
                        data: 'status',
                        orderable: false,
                        searchable: true
                    },
                    {
                        data: 'created_at',
                        orderable: false,
                        searchable: true
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: true
                    },
                ],
                columnDefs: [{
                    targets: [0, 7],
                    className: 'text-center',
                }, ],
            });

            $('#btn-search').on('click', function() {
                $('#table').DataTable().ajax.reload();
            });
        });
    </script>
@endsection

@section('footer')
    @include('layouts.footer')
@endsection
