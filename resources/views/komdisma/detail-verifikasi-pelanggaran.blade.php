@extends('main')

@section('content')
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('show.verifikasi') }}">Verifikasi Pelanggaran</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detail Pelanggaran</li>
            </ol>
        </nav>


        <!-- Card Filter -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="form-group col-md-4 col-sm-12">
                        <img src="{{ asset("storage/upload/tingkat$detail->tingkat/$detail->bukti_foto") }}" class="img-fluid"
                            width="300">
                    </div>
                    <div class="col-md-8 col-sm-12">
                        <form action="{{ route('update.verifikasi') }}" id="form-normal" method="post"
                            class="needs-validation" novalidate>
                            @csrf
                            <input type="hidden" class="form-control" value="{{ $detail->id_pelanggaran }}"
                                name="id_pelanggaran">
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="exampleInputEmail1">Nama</label>
                                    <input type="text" class="form-control" disabled
                                        value="{{ $detail->nama_mahasiswa }}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="exampleInputEmail1">NIM</label>
                                    <input type="text" class="form-control" disabled value="{{ $detail->nim }}">
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="exampleInputEmail1">Prodi</label>
                                    <input type="text" class="form-control" disabled value="{{ $detail->kode_prodi }}">
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="exampleInputEmail1">Tingkat</label>
                                    <input type="text" class="form-control" disabled
                                        value="Tingkat {{ $detail->tingkat }}">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="exampleInputEmail1">Tanggal Pelanggaran</label>
                                    <input type="text" class="form-control" disabled
                                        value="{{ $detail->tanggal_lengkap }}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="exampleInputEmail1">Jam</label>
                                    <input type="text" class="form-control" disabled value="{{ $detail->jam }}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="exampleInputEmail1">Pelapor</label>
                                    <input type="text" class="form-control" disabled
                                        value="{{ $detail->nama_pelapor }}">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="exampleInputEmail1">Kategori Pelanggaran</label>
                                    <input type="text" class="form-control" disabled
                                        value="{{ $detail->nama_kategori }}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="exampleInputEmail1">Jenis Pelanggaran</label>
                                    <input type="text" class="form-control" disabled value="{{ $detail->nama_jenis }}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="exampleInputEmail1">Lokasi</label>
                                    <input type="text" class="form-control" disabled value="{{ $detail->nama_lokasi }}">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="exampleInputEmail1">Keterangan</label>
                                    <textarea class="form-control" rows="2" disabled>{{ $detail->keterangan }}</textarea>
                                </div>
                                <div class="form-group col-md-7">
                                    <label for="exampleInputEmail1">Sanksi*</label>
                                    <select class="custom-select" name="id_sanksi" required>
                                        <option value="" disabled selected>Pilih...</option>
                                        @foreach ($sanksi as $s)
                                            <option value="{{ $s->id }}">
                                                <span>{{ $s->nama }}</span>
                                                {{ !empty($s->lapor) ? ' - ' . $s->lapor . 'x lapor' : '' }}
                                                {{ !empty($s->skorsing) ? ' - ' . $s->skorsing . ' hari skorsing' : '' }}
                                                {{ $s->drop_out == 1 ? '- Drop Out' : '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">
                                        Silahkan pilih sanksi
                                    </div>
                                </div>
                                <div class="form-group col-md-1 align-self-start">
                                    <label
                                        class="form-label">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                    <a class="btn btn-primary btn-small btn-block" id="btn-collapse" href="#"><i
                                            class="fas fa-plus"></i></a>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-auto">
                                    <button type="submit" class="btn btn-success btn-block">Verifikasi</button>
                                </div>
                            </div>
                        </form>
                        <form action="{{ route('update.new.verifikasi') }}" id="form-collapse" method="post"
                            class="needs-validation d-none" novalidate>
                            @csrf
                            <input type="hidden" class="form-control" value="{{ $detail->id_pelanggaran }}"
                                name="id_pelanggaran">
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="exampleInputEmail1">Nama</label>
                                    <input type="text" class="form-control" disabled
                                        value="{{ $detail->nama_mahasiswa }}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="exampleInputEmail1">NIM</label>
                                    <input type="text" class="form-control" disabled value="{{ $detail->nim }}">
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="exampleInputEmail1">Prodi</label>
                                    <input type="text" class="form-control" disabled
                                        value="{{ $detail->kode_prodi }}">
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="exampleInputEmail1">Tingkat</label>
                                    <input type="text" class="form-control" disabled
                                        value="Tingkat {{ $detail->tingkat }}">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="exampleInputEmail1">Tanggal Pelanggaran</label>
                                    <input type="text" class="form-control" disabled
                                        value="{{ $detail->tanggal_lengkap }}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="exampleInputEmail1">Jam</label>
                                    <input type="text" class="form-control" disabled value="{{ $detail->jam }}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="exampleInputEmail1">Pelapor</label>
                                    <input type="text" class="form-control" disabled
                                        value="{{ $detail->nama_pelapor }}">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="exampleInputEmail1">Kategori Pelanggaran</label>
                                    <input type="text" class="form-control" disabled
                                        value="{{ $detail->nama_kategori }}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="exampleInputEmail1">Jenis Pelanggaran</label>
                                    <input type="text" class="form-control" disabled
                                        value="{{ $detail->nama_jenis }}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="exampleInputEmail1">Lokasi</label>
                                    <input type="text" class="form-control" disabled
                                        value="{{ $detail->nama_lokasi }}">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="exampleInputEmail1">Keterangan</label>
                                    <textarea class="form-control" name="keterangan" rows="2" disabled>{{ $detail->keterangan }}</textarea>
                                </div>
                                <div class="form-group col-md-5 col-sm-12">
                                    <label for="exampleInputEmail1">Nama Sanksi*</label>
                                    <input type="text" class="form-control" name="nama_sanksi"
                                        placeholder="Masukkan nama sanksi" maxlength="50" required>
                                    <div class="invalid-feedback">Nama sanksi tidak boleh kosong</div>
                                </div>
                                <div class="form-group col-md-2 col-sm-12">
                                    <label for="exampleInputEmail1">Lapor*</label>
                                    <input type="number" class="form-control" name="lapor" value="0">
                                    <div class="invalid-feedback">Lapor tidak boleh kosong</div>
                                </div>
                                <div class="form-group col-md-2 col-sm-12">
                                    <label for="exampleInputEmail1">Skorsing*</label>
                                    <input type="number" class="form-control" name="skorsing" value="0">
                                    <div class="invalid-feedback">Skorsing tidak boleh kosong</div>
                                </div>
                                <div class="form-group col-md-2 col-sm-12">
                                    <label for="exampleInputEmail1">Drop Out*</label>
                                    <select class="custom-select" name="drop_out">
                                        <option value="0" selected>Tidak</option>
                                        <option value="1">Ya</option>
                                    </select>
                                    <div class="invalid-feedback">Drop out tidak boleh kosong</div>
                                </div>
                                <div class="form-group col-md-1 col-sm-12 align-self-start">
                                    <label
                                        class="form-label">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                    <a class="btn btn-primary btn-small btn-block" href="#" id="btn-hidden"><i
                                            class="fas fa-minus"></i></a>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-auto">
                                    <button type="submit" class="btn btn-success btn-block">Verifikasi</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="accordion mb-4" id="accordionExample">
            <!-- Daftar Pelanggaran -->
            <div class="card">
                <div class="card-header p-0 bg-blue" id="headingOne">
                    <h6 class="m-0">
                        <button class="btn btn-block text-white text-left" type="button" data-toggle="collapse"
                            data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            <i class="fas fa-angle-down mr-3"></i>Daftar pelanggaran mahasiswa
                        </button>
                    </h6>
                </div>

                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                    data-parent="#accordionExample">
                    <div class="card-body">
                        @if ($pelanggaran->isEmpty())
                            <h6 class="text-center">Tidak ada pelanggaran</h6>
                        @else
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">No</th>
                                            <th scope="col">Id</th>
                                            <th scope="col">Tanggal</th>
                                            <th scope="col">Jam</th>
                                            <th scope="col">Lokasi</th>
                                            <th scope="col">Kategori Pelanggaran</th>
                                            <th scope="col">Jenis Pelanggaran</th>
                                            <th scope="col">Keterangan</th>
                                            <th scope="col">Sanksi</th>
                                            <th scope="col">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $no = 1;
                                        @endphp
                                        @foreach ($pelanggaran as $p)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td>{{ $p->pelanggaran_mahasiswa_id }}</td>
                                                <td>{{ $p->tanggal }}</td>
                                                <td>{{ $p->jam }}</td>
                                                <td>{{ $p->nama_lokasi }}</td>
                                                <td>{{ $p->nama_kategori }}</td>
                                                <td>{{ $p->nama_jenis }}</td>
                                                <td>{{ $p->keterangan }}</td>
                                                <td>
                                                    @if (empty($p->nama_sanksi))
                                                        <span class='text-danger'>Perlu verifikasi</span>
                                                    @else
                                                        {{ $p->nama_sanksi }}
                                                    @endif
                                                </td>
                                                <td>
                                                    @switch($p->status)
                                                        @case('Menunggu')
                                                            <span class="badge badge-outline-danger">Menunggu
                                                                verifikasi</span>
                                                        @break

                                                        @case('Drop Out')
                                                            <span class="badge badge-outline-danger">Drop
                                                                Out</span>
                                                        @break

                                                        @case('Belum mengisi jadwal')
                                                            <span class="badge badge-outline-warning">Proses</span>
                                                        @break

                                                        @case('Jadwal belum lengkap')
                                                            <span class="badge badge-outline-warning">Proses</span>
                                                        @break

                                                        @case('Proses')
                                                            <span class="badge badge-outline-warning">Proses</span>
                                                        @break

                                                        @case('Sedang diskors')
                                                            <span class="badge badge-outline-warning">Sedang
                                                                diskors</span>
                                                        @break

                                                        @case('Selesai')
                                                            <span class="badge badge-outline-success">Selesai</span>
                                                        @break

                                                        @default
                                                    @endswitch
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <!-- End Daftar Pelanggaran -->
            <!-- Daftar Skorsing Mahasiswa -->
            <div class="card">
                <div class="card-header p-0 bg-blue" id="headingOne">
                    <h6 class="m-0">
                        <button class="btn btn-block text-white text-left" type="button" data-toggle="collapse"
                            data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseOne">
                            <i class="fas fa-angle-down mr-3"></i>Daftar skorsing mahasiswa
                        </button>
                    </h6>
                </div>

                <div id="collapseTwo" class="collapse show" aria-labelledby="headingOne"
                    data-parent="#accordionExample">
                    <div class="card-body">
                        @if ($skorsing->isEmpty())
                            <h6 class="text-center">Tidak ada pelanggaran</h6>
                        @else
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="row">No</th>
                                            <th>Id Pelanggaran</th>
                                            <th>Nama</th>
                                            <th>Prodi</th>
                                            <th>Tanggal Berakhir</th>
                                            <th>Lama Skorsing</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $no = 1;
                                        @endphp
                                        @foreach ($skorsing as $s)
                                            <tr>
                                                <td class="align-middle">{{ $no++ }}</td>
                                                <td class="align-middle">{{ $s->pelanggaran_id }}</td>
                                                <td class="align-middle">{{ $s->nama_mahasiswa }}</td>
                                                <td class="align-middle">{{ $s->prodi }}</td>
                                                <td class="align-middle">
                                                    {{ !empty($s->tgl_berakhir) ? $s->tgl_berakhir : '00/00/0000' }}
                                                </td>
                                                <td class="align-middle">{{ $s->jum_hari }} Hari</td>
                                                <td>
                                                    @switch($s->status)
                                                        @case('Belum mengisi jadwal')
                                                            <span class='badge badge-outline-danger'>{{ $s->status }}</span>
                                                        @break

                                                        @case('Skorsing belum dimulai')
                                                            <span class='badge badge-outline-secondary'>{{ $s->status }}</span>
                                                        @break

                                                        @case('Sedang diskors')
                                                            <span class='badge badge-outline-warning'>{{ $s->status }}</span>
                                                        @break

                                                        @case('Selesai')
                                                            <span class='badge badge-outline-success'>{{ $s->status }}</span>
                                                        @break

                                                        @default
                                                    @endswitch
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <!-- End Daftar Skorsing Mahasiswa -->
        </div>

    </div>
@endsection
