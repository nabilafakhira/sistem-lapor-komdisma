@extends('main')

@section('content')
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Pengajuan Surat Keterangan Kelakuan Baik</li>
            </ol>
        </nav>

        <div class="alert alert-warning d-none" role="alert" id="alertSurat">
            <div class="d-flex align-items-center" href="#">
                <div class="mr-3">
                    <i class='bx bx-info-circle h2 text-warning mb-0'></i>
                </div>
                <div>
                    <span class="font-weight-bold text-warning">Data baru telah ditambahkan! Silahkan reload table untuk
                        melihat data baru</span>
                </div>
            </div>
        </div>

        <!-- Card Filter -->
        <div class="card shadow mb-4">
            <div class="card-body">
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
            </div>
        </div>

        <!-- Table Pelanggaran -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="tablePengajuan" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th scope="row">No</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Nama</th>
                                <th>Prodi</th>
                                <th>NIM</th>
                                <th>Keperluan</th>
                                <th>Tanggal Berakhir</th>
                                <th>Inspektur</th>
                                <th>Komentar</th>
                                <th>Status</th>
                                <th class="w-10">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pengajuan as $row)
                                <tr>
                                    <td class="align-middle"></td>
                                    <td class="align-middle">{{ $row->tgl_pengajuan }}</td>
                                    <td class="align-middle">{{ $row->nama_mahasiswa }}</td>
                                    <td class="align-middle">{{ $row->kode_prodi }}</td>
                                    <td class="align-middle">{{ $row->nim }}</td>
                                    <td class="align-middle">{{ $row->keperluan }}</td>
                                    <td class="align-middle">
                                        @if (!empty($row->tgl_berakhir) && empty($row->komentar))
                                            {{ $row->tgl_berakhir }}
                                        @elseif (empty($row->tgl_berakhir) && !empty($row->komentar))
                                            -
                                        @else
                                            <span class='text-danger'>Belum diverifikasi</span>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        {!! !empty($row->nama_inspektur) ? $row->nama_inspektur : '<span class="text-danger">Belum diverifikasi</span>' !!}
                                    </td>
                                    <td class="align-middle">{{ !empty($row->komentar) ? $row->komentar : '-' }}</td>
                                    <td class="align-middle">
                                        @if ($row->status == 0 && empty($row->komentar))
                                            <span class='badge badge-outline-danger'>Menunggu verifikasi</span>
                                        @elseif ($row->status == 0 && !empty($row->komentar))
                                            <span class='badge badge-outline-secondary'>Ditolak</span>
                                        @else
                                            <span class='badge badge-outline-success'>Disetujui</span>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        @if ($row->status == 1 || !empty($row->komentar))
                                            <button class="btn rounded-circle btn-outline-secondary btn-sm mr-1" disabled><i
                                                    class="fas fa-check"></i>
                                            </button>
                                            <button class="btn rounded-circle btn-outline-secondary btn-sm" disabled><i
                                                    class="fas fa-times px-15"></i>
                                            </button>
                                        @else
                                            <a href="#"
                                                class="btn rounded-circle btn-outline-primary btn-sm mr-1 btnTerima"
                                                data-toggle="modal" data-target="#verifPengajuan"
                                                data-id="{{ $row->id }}" data-nama="{{ $row->nama_mahasiswa }}"
                                                data-nim="{{ $row->nim }}" data-prodi="{{ $row->nama_prodi }}"
                                                data-keperluan="{{ $row->keperluan }}"><i class="fas fa-check"></i>
                                            </a>
                                            <a href="#" class="btn rounded-circle btn-outline-danger btn-sm btnTolak"
                                                data-toggle="modal" data-target="#tolakPengajuan"
                                                data-id="{{ $row->id }}"><i class="fas fa-times px-15"></i>
                                            </a>
                                            </a>
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

    {{-- Modal terima pengajuan --}}
    <div class="modal fade" id="verifPengajuan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Verifikasi Pengajuan Surat</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ route('terima.surat.kelakuan.baik') }}" method="POST" class="needs-validation"
                    novalidate>
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="id" id="id">
                        <div class="form-group">
                            <label>Nama</label>
                            <input type="text" class="form-control" name="nama" id="nama" disabled>
                        </div>
                        <div class="form-group">
                            <label>NIM</label>
                            <input type="text" class="form-control" name="nim" id="nim" disabled>
                        </div>
                        <div class="form-group">
                            <label>Program Studi</label>
                            <textarea class="form-control" name="prodi" id="prodi" rows="2" disabled></textarea>
                        </div>
                        <div class="form-group">
                            <label>Keperluan</label>
                            <textarea class="form-control" name="keperluan" id="keperluan" rows="2" disabled></textarea>
                        </div>
                        <div class="form-group">
                            <label>Tanggal Berakhir Masa Berlaku Surat</label>
                            <input type="date" class="form-control" name="tgl_berakhir" required>
                            <div class="invalid-feedback">Tanggal berakhir tidak boleh kosong</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a class="btn" data-dismiss="modal">Batal</a>
                        <button type="submit" class="btn btn-success">Setuju</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Tolak Pengajuan -->
    <div class="modal fade" id="tolakPengajuan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Verifikasi Pengajuan Surat</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ route('tolak.surat.kelakuan.baik') }}" method="POST" class="needs-validation"
                    novalidate>
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="id" id="id">
                        <div class="form-group">
                            <label>Alasan Penolakan</label>
                            <textarea class="form-control" rows="3" placeholder="Masukkan alasan penolakan" name="komentar"
                                maxlength="100" required></textarea>
                            <div class="invalid-feedback">Alasan penolakan tidak boleh kosong</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a class="btn" data-dismiss="modal">Batal</a>
                        <button type="submit" class="btn btn-danger">Tolak</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        var tablePengajuan = $('#tablePengajuan').DataTable({
            "responsive": true,
            "columnDefs": [{
                "targets": 1,
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

        tablePengajuan.on('order.dt search.dt', function() {
            tablePengajuan.column(0, {
                search: 'applied',
                order: 'applied'
            }).nodes().each(function(cell, i) {
                cell.innerHTML = i + 1;
            });
        }).draw();

        $('#prodi').on('change', function() {
            tablePengajuan.columns(3).search(this.value).draw();
        });
        $('#status').on('change', function() {
            tablePengajuan.columns(9).search(this.value).draw();
        });

        $(document).on("click", '.btnTerima', function() {
            $('#verifPengajuan #id').val($(this).data('id'));
            $('#verifPengajuan #nama').val($(this).data('nama'));
            $('#verifPengajuan #nim').val($(this).data('nim'));
            $('#verifPengajuan #prodi').val($(this).data('prodi'));
            $('#verifPengajuan #keperluan').val($(this).data('keperluan'));
        });
        $(document).on("click", '.btnTolak', function() {
            $('#tolakPengajuan #id').val($(this).data('id'));
        });
    </script>
@endpush
