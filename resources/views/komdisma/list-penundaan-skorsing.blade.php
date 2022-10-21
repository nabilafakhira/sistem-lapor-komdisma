@extends('main')

@section('content')
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Penundaan Skorsing Mahasiswa</li>
            </ol>
        </nav>

        <div class="alert alert-warning d-none" role="alert" id="alertPenundaan">
            <div class="d-flex align-items-center" href="#">
                <div class="mr-3">
                    <i class='bx bx-info-circle h2 text-warning mb-0'></i>
                </div>
                <div>
                    <span class="font-weight-bold text-warning">Data baru telah ditambahkan! Silahkan reload table untuk melihat data baru</span>
                </div>
            </div>
        </div>

        <!-- Card Filter -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <form>
                    <div class="form-row align-items-center justify-content-start">
                        <div class="col-md-auto col-sm-12 pt-2">
                            <h6>Filter</h6>
                        </div>
                        <div class="col-md-auto col-sm-12 py-1">
                            <select class="form-control" id="prodi">
                                <option value="" selected>Semua Prodi</option>
                                @foreach ($prodi as $row)
                                    <option value="{{ $row->kode }}">{{ $row->kode }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-auto col-sm-12 py-1">
                            <select class="form-control" id="status">
                                <option value="" selected>Semua Status</option>
                                <option value="Menunggu verifikasi">Menunggu verifikasi</option>
                                <option value="Disetujui">Disetujui</option>
                                <option value="Ditolak">Ditolak</option>
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
                    <table class="table table-striped" id="tablePenundaan" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th scope="row">No</th>
                                <th>Id Pelanggaran</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Nama</th>
                                <th>Prodi</th>
                                <th>NIM</th>
                                <th>Keperluan</th>
                                <th>Inspektur</th>
                                <th>Komentar</th>
                                <th>Status</th>
                                <th class="w-10">Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($skorsing as $row)
                                <tr>
                                    <td class="align-middle"></td>
                                    <td class="align-middle">
                                        <form action="{{ route('detail.pelanggaran') }}" method="post">
                                            @csrf
                                            <input type="hidden" value="{{ $row->pelanggaran_mahasiswa_id }}" name="id">
                                            <button class="btn btn-link ">{{ $row->pelanggaran_mahasiswa_id }}</button>
                                        </form>
                                    </td>
                                    <td class="align-middle">{{ $row->tgl_pengajuan }}</td>
                                    <td class="align-middle">{{ $row->nama }}</td>
                                    <td class="align-middle">{{ $row->kode_prodi }}</td>
                                    <td class="align-middle">{{ $row->nim }}</td>
                                    <td class="align-middle">{{ $row->keterangan }}</td>
                                    <td class="align-middle">
                                        @if ($row->inspektur != null)
                                            {{ $row->nama_inspektur }}
                                        @else
                                            <span class='text-danger'>Belum diverifikasi</span>
                                        @endif
                                    </td>
                                    <td class="align-middle">{{ $row->komentar != null ? $row->komentar : '-' }}</td>
                                    <td class="align-middle">
                                        @if ($row->status == 0 && $row->komentar == null)
                                            <span class='badge badge-outline-danger'>Menunggu verifikasi</span>
                                        @elseif ($row->status == 0 && $row->komentar != null)
                                            <span class='badge badge-outline-secondary'>Ditolak</span>
                                        @else
                                            <span class='badge badge-outline-success'>Disetujui</span>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        @if ($row->status == 1 || $row->komentar != null)
                                            <button class="btn rounded-circle btn-outline-secondary btn-sm mr-1" disabled><i
                                                    class="fas fa-search"></i>
                                            </button>
                                        @else
                                        <form action="{{ route('detail.penundaan.skorsing') }}" method="post">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $row->pelanggaran_mahasiswa_id }}">
                                            <input type="hidden" name="nim" value="{{ $row->nim }}">
                                            <button type ="submit" class="btn rounded-circle btn-outline-primary btn-sm mr-1"><i
                                                class="fas fa-search"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
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

            var tablePenundaan = $('#tablePenundaan').DataTable({
                "responsive": true,
                "columnDefs": [{
                    "targets": 2,
                    "sType": "date-uk"
                },{
                    "targets": [0, -1],
                    "orderable": false
                }],
                dom: "<'row'<'col-sm-12 col-md-4'l><'text-right col-sm-12 col-md-6 ml-auto'f><'col-sm-12 col-md-auto mx-0 px-0'B>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                renderer: 'bootstrap',
                buttons: [{
                    text: 'Reload Table',
                    className: 'btn btn-secondary btn-sm btnreload',
                    action: function(e, dt, node, config) {
                        location.reload()
                    }
                }, ],
            });
            
            tablePenundaan.on('order.dt search.dt', function() {
                tablePenundaan.column(0, {
                    search: 'applied',
                    order: 'applied'
                }).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1;
                });
            }).draw();
            
            $('#prodi').on('change', function() {
                tablePenundaan.columns(4).search(this.value).draw();
            });
            $('#status').on('change', function() {
                tablePenundaan.columns(9).search(this.value).draw();
            });

            $('.btnTolak').on('click', function() {
                $('#id').val($(this).data('id'));
            });

            $('.btnTerima').on('click', function() {
                $('#id_penundaan').val($(this).data('id'));
                $('#nama_mahasiswa').html($(this).data('nama'))
                $('#nim_mahasiswa').html($(this).data('nim'))
                $('#prodi_mahasiswa').html($(this).data('prodi'))
                $('#tgl_pengajuan_lengkap').html($(this).data('tanggal'))
                $('#keterangan').html($(this).data('keterangan'))
            });
        });
    </script>
@endpush
