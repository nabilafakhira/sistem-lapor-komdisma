@extends('main')

@section('content')
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('mahasiswa.show.pelanggaran') }}">Pelanggaran</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detail Pelanggaran</li>
            </ol>
        </nav>

        <div class="row justify-content-between align-items-center mb-3">
            <div class="form-group col-md-3 col-sm-12">
                <div>Status
                    @switch($pelanggaran->status)
                        @case('Menunggu')
                            <span class="badge badge-danger ml-3">
                                <div class="h6 m-0">Menunggu verifikasi</div>
                            </span>
                        @break

                        @case('Drop Out')
                            <span class="badge badge-danger ml-3">
                                <div class="h6 m-0">{{ $pelanggaran->status }}</div>
                            </span>
                        @break

                        @case('Belum mengisi jadwal')
                            <span class="badge badge-danger ml-3">
                                <div class="h6 m-0">{{ $pelanggaran->status }}</div>
                            </span>
                        @break

                        @case('Proses')
                            <span class="badge badge-warning ml-3">
                                <div class="h6 m-0">{{ $pelanggaran->status }}</div>
                            </span>
                        @break

                        @case('Sedang diskors')
                            <span class="badge badge-warning ml-3">
                                <div class="h6 m-0">{{ $pelanggaran->status }}</div>
                            </span>
                        @break

                        @case('Selesai')
                            <span class="badge badge-success ml-3">
                                <div class="h6 m-0">{{ $pelanggaran->status }}</div>
                            </span>
                        @break

                        @default
                    @endswitch
                </div>
            </div>
            <div class="col-md-4 col-sm-12 text-right">
                @if (($pelanggaran->status == 'Selesai' or $pelanggaran->status == 'Drop Out') and $pelanggaran->lapor != null)
                    <form method="POST" action="{{ route('unduh.surat.bebas') }}" class="needs-validation" novalidate>
                        @csrf
                        <input type="hidden" name="jum_lapor" value="{{ $pelanggaran->lapor ? $pelanggaran->lapor : '' }}">
                        <input type="hidden" name="tgl_surat_bebas"
                            value="{{ $pelanggaran->tgl_surat_bebas ? $pelanggaran->tgl_surat_bebas_lengkap : '' }}">
                        <input type="hidden" name="nama_inspektur"
                            value="{{ $pelanggaran->inspektur ? $pelanggaran->nama_inspektur : '' }}">
                        <input type="hidden" name="tgl_terakhir_lapor"
                            value="{{ $lastLapor != null ? $lastLapor : $pelanggaran->tgl_surat_bebas_lengkap }}">
                        <input type="hidden" name="prodi" value="{{ $pelanggaran->nama_prodi }}">
                        <button type="submit" class="btn btn-primary"><i class='fas fa-download mr-2'></i>Unduh surat bebas
                            lapor</button>
                    </form>
                @endif
                @if ($canLapor['hasil'] == true and $pelanggaran->status != 'Sedang diskors')
                    <a class="btn btn-primary" href="#" data-toggle="modal" data-target="#Lapor">Ajukan lapor</a>
                @elseif ($canLapor['hasil'] == false)
                    @if ($canLapor['keterangan'] == 'Jadwal belum lengkap')
                        <form action="{{ route('mahasiswa.add.jadwal') }}" method="post">
                            @csrf
                            <input type="hidden" value="{{ $pelanggaran->id_pelanggaran }}" name="id">
                            <button class="btn btn-primary"><i class="fas fa-plus mr-2"></i>Tambah jadwal</button>
                        </form>
                        <small class="form-text">
                            <i class="fas fa-exclamation-circle mr-1"></i>Tambahkan jadwal matkul skorsing
                        </small>
                    @elseif ($canLapor['keterangan'] == 'Belum diverifikasi' or
                        $canLapor['keterangan'] == 'Lapor lengkap' or
                        $canLapor['keterangan'] == 'Tidak ada lapor')
                    @else
                        <button class="btn btn-secondary" disabled>Ajukan Lapor</button>
                        <small class="form-text">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $canLapor['keterangan'] }}
                        </small>
                    @endif
                @elseif ($pelanggaran->status == 'Sedang diskors')
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 col-sm-12">
                <!-- Detail Pelanggaran -->
                <div class="card shadow mb-4">
                    <div class="card-header bg-blue">
                        <h6 class="m-0 font-weight-bold">Detail Pelanggaran</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-md-4 col-sm-12">
                                <label for="exampleInputEmail1">Tanggal Pelanggaran</label>
                                <input type="text" class="form-control" disabled
                                    value="{{ $pelanggaran->tanggal_lengkap }}">
                            </div>
                            <div class="form-group col-md-4 col-sm-12">
                                <label for="exampleInputEmail1">Jam Pelanggaran</label>
                                <input type="text" class="form-control" disabled value="{{ $pelanggaran->jam }}">
                            </div>
                            <div class="form-group col-md-4 col-sm-12">
                                <label for="exampleInputEmail1">Lokasi Pelanggaran</label>
                                <input type="text" class="form-control" disabled
                                    value="{{ $pelanggaran->nama_lokasi }}">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="exampleInputEmail1">Kategori Pelanggaran</label>
                                <input type="text" class="form-control" disabled
                                    value="Pelanggaran {{ $pelanggaran->nama_kategori }}">
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="exampleInputEmail1">Jenis Pelanggaran</label>
                                <input type="text" class="form-control" disabled value="{{ $pelanggaran->nama_jenis }}">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="exampleInputEmail1">Keterangan</label>
                                <input type="text" class="form-control" disabled value="{{ $pelanggaran->keterangan }}">
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="exampleInputEmail1">Pelapor</label>
                                <input type="text" class="form-control" disabled
                                    value="{{ $pelanggaran->nama_pelapor }}">
                            </div>
                        </div>
                        @if (!empty($pelanggaran->sanksi_id) and !empty($pelanggaran->inspektur))
                            <div class="form-row">
                                <div class="form-group col-md-6 col-sm-12">
                                    <label for="exampleInputEmail1">Sanksi</label>
                                    <input type="text" class="form-control" disabled
                                        value="{{ $pelanggaran->nama_sanksi }}">
                                </div>
                                <div class="form-group col-md-6 col-sm-12">
                                    <label for="exampleInputEmail1">Inspektur</label>
                                    <input type="text" class="form-control" disabled
                                        value="{{ $pelanggaran->nama_inspektur }}">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4 col-sm-12">
                                    <label for="exampleInputEmail1">Lapor</label>
                                    <input type="text" class="form-control" disabled
                                        value="{{ empty($pelanggaran->lapor) ? '-' : "$pelanggaran->lapor Kali" }}">
                                </div>
                                <div class="form-group col-md-4 col-sm-12">
                                    <label for="exampleInputEmail1">Skorsing</label>
                                    <input type="text" class="form-control" disabled
                                        value="{{ empty($pelanggaran->skorsing) ? '-' : "$pelanggaran->skorsing Hari" }}">
                                </div>
                                <div class="form-group col-md-4 col-sm-12">
                                    <label for="exampleInputEmail1">Drop out</label>
                                    <input type="text" class="form-control" disabled
                                        value="{{ $pelanggaran->drop_out == 0 ? '-' : 'Ya' }}">
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Lapor Pelanggaran -->
        @if (!$lapor->isEmpty())
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div class="card shadow mb-4">
                        <div class="card-header bg-blue">
                            <h6 class="m-0 font-weight-bold">Detail Lapor</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th scope="row">No</th>
                                            <th>Tanggal Lapor</th>
                                            <th>Penerima Lapor</th>
                                            <th>Keterangan</th>
                                            <th>Status Lapor</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $no = 1;
                                        @endphp
                                        @foreach ($lapor as $l)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td>{{ $l->tanggal }}</td>
                                                <td>{{ $l->nama_penerima_lapor }}</td>
                                                <td>{{ empty($l->keterangan) ? '-' : $l->keterangan }}</td>
                                                <td>
                                                    @if ($l->status == 1)
                                                        <span class="badge badge-outline-success">Diterima</span>
                                                    @else
                                                        <span class="badge badge-outline-warning">Proses</span>
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
            </div>
        @endif


        {{-- Jadwal matkul skorsing --}}
        @if (!$jadwal_skorsing->isEmpty())
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div class="card shadow mb-4">
                        <div class="card-header bg-blue">
                            <h6 class="m-0 font-weight-bold">Detail Skorsing</h6>
                        </div>
                        <div class="card-body">
                            <div class="row justify-content-between align-items-center mb-3">
                                <div class="col-sm-12">
                                    @if ($cekPenundaan != null and ($statusSkorsing != 'Selesai' and $statusSkorsing != 'Sedang diskors'))
                                        @if ($cekPenundaan->status == 0 and $cekPenundaan->komentar == null)
                                            <button type="button" class="btn btn-primary" disabled>Ajukan penundaan
                                                skorsing</button>
                                            <small class="form-text"><i
                                                    class="fas fa-exclamation-circle mr-1"></i>Pengajuan penundaan skorsing
                                                menunggu diverifikasi</small>
                                        @elseif ($cekPenundaan->status == 0 and $cekPenundaan->komentar != null)
                                            <form action="{{ route('form.penundaan.skorsing') }}" method="post">
                                                @csrf
                                                <input type="hidden" name="id"
                                                    value="{{ $pelanggaran->id_pelanggaran }}">
                                                <button type="submit" class="btn btn-primary">Ajukan penundaan
                                                    skorsing</button>
                                            </form>
                                            <small class='form-text text-danger'><i
                                                    class='fas fa-exclamation-circle mr-1'></i>Pengajuan penundaan skorsing
                                                tanggal {{ $cekPenundaan->tgl_pengajuan_lengkap }} ditolak. Silahkan ajukan
                                                kembali!</small><small class='form-text text-danger'>Alasan ditolak:
                                                {{ $cekPenundaan->komentar }}</small>
                                        @else
                                            <button type="button" class="btn btn-primary" disabled>Ajukan penundaan
                                                skorsing</button>
                                            <small class="form-text text-success"><i
                                                    class="fas fa-check-circle mr-1"></i>Penundaan skorsing
                                                disetujui</small>
                                        @endif
                                    @else
                                        @if ($statusSkorsing != 'Selesai' and $statusSkorsing != 'Sedang diskors')
                                            <form action="{{ route('form.penundaan.skorsing') }}" method="post">
                                                @csrf
                                                <input type="hidden" name="id"
                                                    value="{{ $pelanggaran->id_pelanggaran }}">
                                                <button type="submit" class="btn btn-primary">Ajukan penundaan
                                                    skorsing</button>
                                            </form>
                                        @endif
                                    @endif
                                </div>
                            </div>
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
                                            @if ($cekPenundaan == null or $cekPenundaan->status == 0)
                                                @if ($statusSkorsing != 'Selesai' and $statusSkorsing != 'Sedang diskors')
                                                    <th>Edit</th>
                                                @endif
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $no = 1;
                                        @endphp
                                        @if ($cekPenundaan != null && !$jadwal_skorsing_baru->isEmpty())
                                            @foreach ($jadwal_skorsing_baru as $row)
                                                <tr>
                                                    <td>{{ $no++ }}</td>
                                                    <td>{{ $row->hari }}</td>
                                                    <td>{{ $row->tanggal_matkul }}</td>
                                                    <td>{{ $row->jam_mulai }} - {{ $row->jam_selesai }}</td>
                                                    <td>{{ $row->matkul }}</td>
                                                    <td>{{ $row->nama_dosen }}</td>
                                                    <td>{{ $row->nama_koordinator }}</td>
                                                    @if ($cekPenundaan == null or $cekPenundaan->status == 0)
                                                        @if ($statusSkorsing != 'Selesai' and $statusSkorsing != 'Sedang diskors')
                                                            <td>
                                                                <a href="#" data-toggle="modal" data-target=""
                                                                    class="btn rounded-circle btn-outline-primary btn-sm"><i
                                                                        class="fas fa-pen"></i></a>
                                                            </td>
                                                        @endif
                                                    @endif
                                                </tr>
                                            @endforeach
                                        @else
                                            @foreach ($jadwal_skorsing as $row)
                                                <tr>
                                                    <td>{{ $no++ }}</td>
                                                    <td>{{ $row->hari }}</td>
                                                    <td>{{ $row->tanggal_matkul }}</td>
                                                    <td>{{ $row->jam_mulai }} - {{ $row->jam_selesai }}</td>
                                                    <td>{{ $row->matkul }}</td>
                                                    <td>{{ $row->nama_dosen }}</td>
                                                    <td>{{ $row->nama_koordinator }}</td>
                                                    @if ($cekPenundaan == null or $cekPenundaan->status == 0)
                                                        @if ($statusSkorsing != 'Selesai' and $statusSkorsing != 'Sedang diskors')
                                                            <td>
                                                                <a href="#" data-toggle="modal" data-target=""
                                                                    class="btn rounded-circle btn-outline-primary btn-sm"><i
                                                                        class="fas fa-pen"></i></a>
                                                            </td>
                                                        @endif
                                                    @endif
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            <div class="row justify-content-start align-items-center mb-0">
                                <div class="col-md-12 col-sm-12">
                                    <div>Status Skorsing
                                        @switch($statusSkorsing)
                                            @case('Belum mengisi jadwal')
                                                <span class='badge badge-danger badge-md ml-3 mb-0'>{{ $statusSkorsing }}</span>
                                            @break

                                            @case('Skorsing belum dimulai')
                                                <span
                                                    class='badge badge-md badge-secondary ml-3 mb-0'>{{ $statusSkorsing }}</span>
                                            @break

                                            @case('Mengajukan penundaan')
                                                <span
                                                    class='badge badge-md badge-secondary ml-3 mb-0'>{{ $statusSkorsing }}</span>
                                            @break

                                            @case('Sedang diskors')
                                                <span class='badge badge-md badge-warning ml-3 mb-0'>{{ $statusSkorsing }}</span>
                                            @break

                                            @case('Selesai')
                                                <span class='badge badge-md badge-success ml-3 mb-0'>{{ $statusSkorsing }}</span>
                                            @break

                                            @default
                                        @endswitch
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif


        <!-- Modal Tambah Lapor -->
        <div class="modal fade" id="Lapor" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Ajukan Lapor</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{ route('mahasiswa.lapor') }}" class="needs-validation"
                            novalidate>
                            @csrf
                            <input type="hidden" name="id_pelanggaran" value="{{ $pelanggaran->id_pelanggaran }}">
                            <div class="form-group">
                                <select class="custom-select select2" id="selectDosen" name="dosen" required
                                    style='width: 100%;'>
                                    <option value="" selected>Pilih dosen</option>
                                </select>
                                <div class="invalid-feedback">Silahkan pilih dosen</div>
                            </div>
                            <button type="submit" id="btn-lapor" class="btn btn-success btn-block">Ajukan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Moda Penundaan Skorsing -->
        <div class="modal fade" id="penundaanSkors" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Pengajuan Penundaan Skorsing</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="#" class="needs-validation" novalidate>
                            @csrf
                            <input type="hidden" name="id_pelanggaran" value="{{ $pelanggaran->id_pelanggaran }}">
                            <div class="form-group">
                                <label>Menunda skorsing untuk keperluan :</label>
                                <textarea class="form-control" name="keterangan" id="keterangan" rows="3" maxlength="100" required></textarea>
                                <div class="invalid-feedback">Keperluan tidak boleh kosong</div>
                            </div>
                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="invalidCheck"
                                        required>
                                    <label class="form-check-label" for="invalidCheck">Saya sudah mengubah jadwal matkul
                                        untuk skorsing dengan jadwal matkul terbaru dan saya setuju jadwal matkul tidak akan
                                        bisa diubah lagi</label>
                                    <div class="invalid-feedback">Ketentuan harus disetujui sebelum penundaan diajukan
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <a class="btn" data-dismiss="modal">Batal</a>
                        <button type="submit" class="btn btn-success">Ajukan</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    @endsection