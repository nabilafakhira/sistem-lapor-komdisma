@extends('main')

@section('content')
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Laporan</li>
            </ol>
        </nav>

        <!-- Card Filter -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="form-row align-items-center justify-content-start">
                    <div class="col-md-auto col-sm-12 pt-2">
                        <h6>Filter</h6>
                    </div>
                    <div class="col-md-auto col-sm-12 py-1">
                        <select class="form-control" id="filterProdi">
                            <option value="" selected>Semua Prodi</option>
                            @foreach ($prodi as $row)
                                <option value="{{ $row->kode }}">{{ $row->kode }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Pelanggaran -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="tableLaporan" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th scope="row">No</th>
                                <th>Tanggal Lapor</th>
                                <th>Nama</th>
                                <th>Prodi</th>
                                <th>Pelanggaran</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal terima lapor --}}
    <div class="modal fade" id="terimaLapor" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Terima Laporan</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('acc.laporan') }}" method="POST">
                        @csrf
                        <input type="hidden" name="lapor_id" id="lapor_id">
                        <textarea class="form-control" name="keterangan" id="keterangan" rows="3"
                            placeholder="Masukkan komentar (opsional)" maxlength="100"></textarea>
                </div>
                <div class="modal-footer">
                    <a class="btn" data-dismiss="modal">Batal</a>
                    <button type="submit" class="btn btn-success">Terima</button>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            var tableLaporan = $('#tableLaporan').DataTable({
                "processing": true,
                "serverSide": true,
                "order": [],
                "ajax": {
                    "url": "{!! route('ajax.laporan') !!}",
                    "type": "POST",
                    "data": function(d) {
                        d.prodi = $('#filterProdi').val();
                        d._token = "{{ csrf_token() }}";
                    }
                },
                "columnDefs": [{
                    "targets": [0],
                    "orderable": false
                }],

            });

            tableLaporan.on('draw.dt', function() {
                var PageInfo = tableLaporan.page.info();
                tableLaporan.column(0, {
                    page: 'current'
                }).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1 + PageInfo.start;
                });
            });

            $('#filterProdi').on('change', function() {
                tableLaporan.ajax.reload();
            });

            setInterval(function() {
                tableLaporan.ajax.reload(); // user paging is not reset on reload
            }, 10000);
        })
    </script>
    <script>
        $(document).on("click", '.btnUpdateLapor', function () {
            $('#lapor_id').val($(this).data('id'));
        });
    </script>
@endpush
