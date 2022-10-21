@extends('main')

@section('content')
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#">Data Master</a></li>
                <li class="breadcrumb-item active" aria-current="page">Akun</li>
            </ol>
        </nav>

        <!-- Card Filter -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="form-row align-items-center justify-content-start">
                    <div class="col-md-auto pt-2">
                        <h6>Filter</h6>
                    </div>
                    <div class="col-md-auto col-sm-12">
                        <select class="form-control filter" id="filterRole">
                            <option value="" selected>Semua Role</option>
                            <option>Super Admin</option>
                            <option>Admin</option>
                            <option>Akademik</option>
                            <option>Dosen</option>
                            <option>Mahasiswa</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Akun -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="tableAkun" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th scope="row">No</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Role</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            var tableAkun = $('#tableAkun').DataTable({
                "processing": true,
                "serverSide": true,
                "order": [],
                "ajax": {
                    "url": "{!! route('ajax.akun') !!}",
                    "type": "POST",
                    "data": function(d) {
                        d.roleUser = $('#filterRole').val();
                        d._token = "{{ csrf_token() }}";
                    }
                },
                "columnDefs": [{
                    "targets": [0],
                    "orderable": false
                }],

            });

            tableAkun.on('draw.dt', function() {
                var PageInfo = tableAkun.page.info();
                tableAkun.column(0, {
                    page: 'current'
                }).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1 + PageInfo.start;
                });
            });

            $('#filterRole').on('change', function() {
                tableAkun.ajax.reload();
            });

            setInterval( function () {
                tableAkun.ajax.reload(null, false); // user paging is not reset on reload
            }, 10000 );
        })
    </script>
@endpush
