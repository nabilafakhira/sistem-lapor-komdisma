@extends('main')

@section('content')
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('show.skorsing') }}">Skorsing Mahasiswa</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detail Skorsing</li>
            </ol>
        </nav>

        <div class="row justify-content-end align-items-center mb-3">
            <div class="col-md-auto col-sm-12">
                <form action="{{ route('terima.penundaan.skorsing') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $detail->id }}">
                    <button type="submit" class="btn btn-success">Terima Pengajuan</button>
                </form>
            </div>
            <div class="col-md-auto col-sm-12">
                <button type="submit" class="btn btn-danger" data-toggle="modal" data-target="#tolakPengajuan"
                    data-id="{{ $detail->id }}">Tolak Pengajuan</button>
            </div>
        </div>

        <!-- Profile -->
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="card shadow mb-4">
                    <div class="card-header bg-blue">
                        <h6 class="m-0 font-weight-bold">Detail</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-md-4 col-sm-12">
                                <label for="exampleInputEmail1">Nama</label>
                                <input type="text" class="form-control" disabled value="{{ $mahasiswa->nama }}">
                            </div>
                            <div class="form-group col-md-4 col-sm-12">
                                <label for="exampleInputEmail1">NIM</label>
                                <input type="text" class="form-control" disabled value="{{ $mahasiswa->nim }}">
                            </div>
                            <div class="form-group col-md-4 col-sm-12">
                                <label for="exampleInputEmail1">Program Studi</label>
                                <input type="text" class="form-control" disabled value="{{ $mahasiswa->prodi->nama }}">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="exampleInputEmail1">Tanggal Pengajuan</label>
                                <input type="text" class="form-control" disabled
                                    value="{{ $detail->tgl_pengajuan_lengkap }}">
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="exampleInputEmail1">Keperluan Pengajuan</label>
                                <textarea class="form-control" rows="1" disabled>{{ $detail->keterangan }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- jadwal baru --}}
            @if ($jadwal_baru != null)
                <div class="col-md-12 col-sm-12">
                    <div class="card shadow mb-4">
                        <div class="card-header bg-blue">
                            <h6 class="m-0 font-weight-bold">Jadwal Baru</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="tb_detailSkors" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th scope="row">No</th>
                                            <th>Hari</th>
                                            <th>Tanggal</th>
                                            <th>Jam</th>
                                            <th>Mata Kuliah (MK)</th>
                                            <th>Dosen MK</th>
                                            <th>Koordinator MK</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $no = 1;
                                        @endphp
                                        @foreach ($jadwal_baru as $row)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td>{{ $row->hari }}</td>
                                                <td>{{ $row->tanggal_matkul }}</td>
                                                <td>{{ $row->jam_mulai }} - {{ $row->jam_selesai }}</td>
                                                <td>{{ $row->matkul }}</td>
                                                <td>{{ $row->nama_dosen }}</td>
                                                <td>{{ $row->nama_koordinator }}</td>
                                            </tr>
                                        @endforeach
                                        @if ($jadwal_baru == null)
                                            <tr>
                                                <td colspan="7" class="text-center">Data Kosong</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- jadwal lama-->
            @if ($jadwal_lama != null)
                <div class="col-md-12 col-sm-12">
                    <div class="card shadow mb-4">
                        <div class="card-header bg-blue">
                            <h6 class="m-0 font-weight-bold">Jadwal Lama</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="tb_detailSkors" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th scope="row">No</th>
                                            <th>Hari</th>
                                            <th>Tanggal</th>
                                            <th>Jam</th>
                                            <th>Mata Kuliah (MK)</th>
                                            <th>Dosen MK</th>
                                            <th>Koordinator MK</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $no = 1;
                                        @endphp
                                        @foreach ($jadwal_lama as $row)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td>{{ $row->hari }}</td>
                                                <td>{{ $row->tanggal_matkul }}</td>
                                                <td>{{ $row->jam_mulai }} - {{ $row->jam_selesai }}</td>
                                                <td>{{ $row->matkul }}</td>
                                                <td>{{ $row->nama_dosen }}</td>
                                                <td>{{ $row->nama_koordinator }}</td>
                                            </tr>
                                        @endforeach
                                        @if ($jadwal_lama == null)
                                            <tr>
                                                <td colspan="7" class="text-center">Data Kosong</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Tolak Pengajuan -->
    <div class="modal fade" id="tolakPengajuan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Verifikasi Penundaan Skorsing</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ route('tolak.penundaan.skorsing') }}" method="POST" class="needs-validation" novalidate>
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="id" value="{{ $detail->id }}">
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
