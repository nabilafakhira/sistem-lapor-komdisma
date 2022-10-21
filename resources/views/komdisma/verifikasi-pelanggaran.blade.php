@extends('main')

@section('content')
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Verifikasi Pelanggaran</li>
            </ol>
        </nav>

        <!-- Card Filter -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <form>
                    <div class="form-row align-items-center justify-content-start">
                        <div class="col-md-auto col-sm-12 pt-2">
                            <h6>Filter</h6>
                        </div>
                        <div class="col-md-auto col-sm-12 py-1">
                            <select class="form-control filter" name="prodi" id="prodi">
                                <option value="" selected>Semua Prodi</option>
                                @foreach ($prodi as $p)
                                    <option value="{{ $p->kode }}">{{ $p->kode }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3  col-sm-12 py-1">
                            <select class="form-control filter" name="kategori" id="kategori">
                                <option selected value="">Semua Kategori Pelanggaran</option>
                                @foreach ($kategoripel as $k)
                                    <option value="{{ $k->nama }}">{{ $k->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3  col-sm-12 py-1">
                            <select class="form-control filter" name="jenis" id="jenis">
                                <option selected value="">Semua Jenis Pelanggaran</option>
                                @foreach ($jenispel as $j)
                                    <option value="{{ $j->nama }}">{{ $j->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3  col-sm-12 py-1">
                            <select class="form-control filter" name="lokasi" id="lokasi">
                                <option selected value="">Semua Lokasi</option>
                                @foreach ($lokasi as $l)
                                    <option value="{{ $l->nama }}">{{ $l->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Table Pelanggaran -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="tableVerifikasi" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th scope="row">No</th>
                                <th>Tanggal Pelanggaran</th>
                                <th>Nama</th>
                                <th>Prodi</th>
                                <th>Kategori Pelanggaran</th>
                                <th>Jenis Pelanggaran</th>
                                <th>Lokasi</th>
                                <th>Verifikasi</th>
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
            var tableVerifikasi = $('#tableVerifikasi').DataTable({
                "responsive": true,
                "processing": true,
                "serverSide": true,
                "order": [],
                "ajax": {
                    "url": "{!! route('ajax.verifikasi') !!}",
                    "type": "POST",
                    "data": function(d) {
                        d.prodi = $('#prodi').val();
                        d.kategori = $('#kategori').val();
                        d.jenis = $('#jenis').val();
                        d.lokasi = $('#lokasi').val();
                        d._token = "{{ csrf_token() }}";
                    }
                },
                "columnDefs": [{
                    "targets": [0],
                    "orderable": false
                }],

            });

            tableVerifikasi.on('draw.dt', function() {
                var PageInfo = tableVerifikasi.page.info();
                tableVerifikasi.column(0, {
                    page: 'current'
                }).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1 + PageInfo.start;
                });
            });

            $('#prodi').on('change', function() {
                console.log($('#prodi').val())
                tableVerifikasi.ajax.reload();
            });
            $('#kategori').on('change', function() {
                console.log($('#kategori').val())
                tableVerifikasi.ajax.reload();
            });
            $('#jenis').on('change', function() {
                tableVerifikasi.ajax.reload();
            });
            $('#lokasi').on('change', function() {
                tableVerifikasi.ajax.reload();
            });

            setInterval(function() {
                tableVerifikasi.ajax.reload(); // user paging is not reset on reload
            }, 10000);
        });
    </script>
@endpush
